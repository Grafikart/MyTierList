export type Movie = {
    id: number;
    title: string;
    imdb_id: string;
    poster: string;
    position: number | null;
    tier: string | null;
    created_at: string; // ISO 8601 datetime string
    updated_at: string; // ISO 8601 datetime string
};
