import type { Config } from "@netlify/functions";
import { asc, eq } from "drizzle-orm";
import { db } from "../../db/index.js";
import { documents } from "../../db/schema.js";

// Public, read-only list of documents stored in Netlify Database.
export default async (req: Request) => {
  if (req.method !== "GET") {
    return new Response("Method not allowed", { status: 405, headers: { Allow: "GET" } });
  }
  try {
    const rows = await db
      .select({
        id: documents.id,
        title: documents.title,
        description: documents.description,
        documentType: documents.documentType,
        year: documents.year,
        version: documents.version,
        status: documents.status,
        url: documents.url,
        publishedAt: documents.publishedAt,
      })
      .from(documents)
      .where(eq(documents.status, "vigente"))
      .orderBy(asc(documents.title));
    return Response.json(rows, { headers: { "Cache-Control": "no-store" } });
  } catch (error) {
    console.error("documentos: database query failed", error);
    return Response.json({ error: "No fue posible consultar la base de datos." }, { status: 500 });
  }
};

export const config: Config = { path: "/api/documentos" };
