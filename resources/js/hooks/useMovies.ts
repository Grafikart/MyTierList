import { ApiError, fetchApi } from '@/api';
import { Movie } from '@/types/movies';
import { arrayMove } from '@dnd-kit/sortable';
import { useCallback, useState } from 'react';

export function useMovies(initialMovies: Movie[]) {
    const [movies, setMovies] = useState(initialMovies);
    const [isFetching, setIsFetching] = useState(false);

    const setTier = useCallback(
        (movieId: number, tier: string) => {
            setIsFetching(true);
            fetchApi(`/api/movies/${movieId}/tier/${tier}`, { method: 'post' })
                .catch((e: ApiError) => alert(e.message))
                .finally(() => setIsFetching(false));
            setMovies((movies) => {
                return movies.map((movie) => {
                    if (movie.id === movieId) {
                        return {
                            ...movie,
                            tier,
                        };
                    }
                    return movie;
                });
            });
        },
        [setMovies],
    );

    const switchMovie = useCallback(
        (movieId: number, oldMovieId: number) => {
            setMovies((movies) => {
                setIsFetching(true);
                const oldIndex = movies.findIndex((m) => m.id === movieId);
                const newIndex = movies.findIndex((m) => m.id === oldMovieId);
                const newMovies = arrayMove(movies, oldIndex, newIndex);
                fetchApi(`/api/movies/move`, {
                    method: 'post',
                    json: {
                        positions: newMovies
                            .filter((movie) => movie.tier === movies[oldIndex].tier)
                            .map((movie, k) => ({
                                id: movie.id,
                                position: k,
                            })),
                    },
                })
                    .catch((e: ApiError) => alert(e.message))
                    .finally(() => setIsFetching(false));
                return newMovies;
            });
        },
        [setMovies],
    );

    return {
        movies,
        isFetching,
        setTier,
        switchMovie,
    };
}
