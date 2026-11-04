import { date, integer, pgTable, serial, text, timestamp, varchar } from "drizzle-orm/pg-core";

// Mirrors the Laravel `documents` table (database/migrations/2026_*).
export const documents = pgTable("documents", {
  id: serial().primaryKey(),
  title: varchar({ length: 255 }).notNull().unique(),
  functionalComponent: varchar("functional_component", { length: 64 }),
  actuaPhase: varchar("actua_phase", { length: 64 }),
  description: text(),
  documentType: varchar("document_type", { length: 64 }).notNull(),
  process: varchar({ length: 64 }),
  institutionalLevel: varchar("institutional_level", { length: 64 }),
  year: integer(),
  dependency: varchar({ length: 255 }),
  topic: varchar({ length: 255 }),
  version: varchar({ length: 32 }),
  status: varchar({ length: 32 }).notNull().default("vigente"),
  url: varchar({ length: 255 }),
  filePath: varchar("file_path", { length: 255 }),
  originalFilename: varchar("original_filename", { length: 255 }),
  publishedAt: date("published_at"),
  createdAt: timestamp("created_at").defaultNow(),
  updatedAt: timestamp("updated_at").defaultNow(),
});
