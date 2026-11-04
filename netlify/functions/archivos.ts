import type { Config } from "@netlify/functions";
import { getStore } from "@netlify/blobs";
import { eq } from "drizzle-orm";
import { db } from "../../db/index.js";
import { documents } from "../../db/schema.js";

const MIME: Record<string, string> = {
  pdf: "application/pdf",
  xlsx: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
  xls: "application/vnd.ms-excel",
  docx: "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
  doc: "application/msword",
  pptx: "application/vnd.openxmlformats-officedocument.presentationml.presentation",
  ppt: "application/vnd.ms-powerpoint",
  csv: "text/csv; charset=utf-8",
  txt: "text/plain; charset=utf-8",
  png: "image/png",
  jpg: "image/jpeg",
  jpeg: "image/jpeg",
};

// Public download of files uploaded through /api/documentos/subir.
export default async (req: Request) => {
  const key = decodeURIComponent(new URL(req.url).pathname.replace(/^\/api\/archivos\//, ""));
  if (!key) return new Response("Not found", { status: 404 });
  const [doc] = await db
    .select({ name: documents.originalFilename, status: documents.status })
    .from(documents)
    .where(eq(documents.filePath, key))
    .limit(1);
  if (!doc || doc.status !== "vigente") return new Response("Not found", { status: 404 });

  const data = await getStore("documentos").get(key, { type: "arrayBuffer" });
  if (!data) return new Response("Not found", { status: 404 });
  const name = doc.name || key.split("/").pop()!;
  const ext = name.split(".").pop()!.toLowerCase();
  return new Response(data, {
    headers: {
      "Content-Type": MIME[ext] ?? "application/octet-stream",
      "Content-Disposition": `attachment; filename*=UTF-8''${encodeURIComponent(name)}`,
      "X-Content-Type-Options": "nosniff",
      "Cache-Control": "public, max-age=300",
    },
  });
};

export const config: Config = { path: "/api/archivos/*", method: "GET" };
