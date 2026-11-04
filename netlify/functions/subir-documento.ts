import type { Config } from "@netlify/functions";
import { getUser } from "@netlify/identity";
import { getStore } from "@netlify/blobs";
import { db } from "../../db/index.js";
import { documents } from "../../db/schema.js";

// Request bodies to Netlify Functions are limited to ~6 MB; keep a safe margin.
const MAX_BYTES = 4 * 1024 * 1024;
const ALLOWED = ["pdf", "xlsx", "xls", "docx", "doc", "pptx", "ppt", "csv", "txt", "png", "jpg", "jpeg"];
const TYPES = ["informe_pdf", "presentacion_pdf", "matriz_excel", "documento", "otro"];

const field = (form: FormData, name: string, max: number) => {
  const value = form.get(name);
  return typeof value === "string" ? value.trim().slice(0, max) : "";
};
const error = (message: string, status: number) => Response.json({ error: message }, { status });

// Lets an authenticated Netlify Identity user upload a document: the file goes to
// Netlify Blobs and its metadata to the `documents` table in Netlify Database.
export default async (req: Request) => {
  if (req.method !== "POST") {
    return new Response("Method not allowed", { status: 405, headers: { Allow: "POST" } });
  }
  const user = await getUser();
  if (!user) return error("Debes iniciar sesión para subir documentos.", 401);

  let form: FormData;
  try {
    form = await req.formData();
  } catch {
    return error("No se pudo leer el formulario.", 400);
  }
  const file = form.get("file");
  const title = field(form, "title", 255);
  if (!(file instanceof File) || file.size === 0) return error("Selecciona un archivo.", 400);
  if (!title) return error("Escribe el nombre del documento.", 400);
  if (file.size > MAX_BYTES) return error("El archivo supera el tamaño máximo de 4 MB.", 413);

  const originalName = file.name.split(/[\\/]/).pop()!.slice(0, 200);
  const ext = originalName.includes(".") ? originalName.split(".").pop()!.toLowerCase() : "";
  if (!ALLOWED.includes(ext)) return error(`Formato no permitido. Usa: ${ALLOWED.join(", ")}.`, 415);

  const type = field(form, "documentType", 64);
  const yearText = field(form, "year", 4);
  const year = /^\d{4}$/.test(yearText) ? Number(yearText) : null;
  const safeName = originalName
    .normalize("NFD").replace(/[̀-ͯ]/g, "")
    .replace(/[^A-Za-z0-9._-]+/g, "_").replace(/^_+|_+$/g, "") || `archivo.${ext}`;
  const key = `${crypto.randomUUID()}/${safeName}`;

  const store = getStore("documentos");
  await store.set(key, await file.arrayBuffer());
  try {
    const [row] = await db
      .insert(documents)
      .values({
        title,
        description: field(form, "description", 2000) || null,
        documentType: TYPES.includes(type) ? type : "documento",
        year,
        version: field(form, "version", 32) || null,
        status: "vigente",
        url: `/api/archivos/${key}`,
        filePath: key,
        originalFilename: originalName,
        uploadedBy: user.email ?? user.id,
        publishedAt: new Date().toISOString().slice(0, 10),
      })
      .returning({ id: documents.id, title: documents.title, url: documents.url });
    return Response.json(row, { status: 201 });
  } catch (err: any) {
    await store.delete(key).catch(() => {});
    const code = err?.code ?? err?.cause?.code;
    if (code === "23505") return error("Ya existe un documento con ese nombre. Usa otro nombre.", 409);
    console.error("subir-documento: insert failed", err);
    return error("No se pudo guardar el documento. Inténtalo de nuevo.", 500);
  }
};

export const config: Config = { path: "/api/documentos/subir" };
