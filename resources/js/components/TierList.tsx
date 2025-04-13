import { useMovies } from '@/hooks/useMovies';
import type { Movie } from '@/types/movies';
import { DndContext, type DragEndEvent, DragOverlay, type DragStartEvent, useDroppable } from '@dnd-kit/core';
import { rectSortingStrategy, SortableContext, useSortable } from '@dnd-kit/sortable';
import { CSS } from '@dnd-kit/utilities';
import clsx from 'clsx';
import { useCallback, useState } from 'react';

type Tier = {
    letter: string;
    title: string;
    description: string;
    color: string;
};

type Props = {
    movies: Movie[];
    tiers: Tier[];
};

export function TierList(props: Props) {
    const { movies, setTier, switchMovie, isFetching } = useMovies(props.movies);
    const [activeMovie, setActiveMovie] = useState<Movie | null>(null);
    const handleDragEnd = useCallback(
        (e: DragEndEvent) => {
            if (!e.over || !e.over.id || typeof e.active.id !== 'number') {
                return;
            }
            const overId = e.over.id;
            // We want to move a movie in the list (sortable scenario)
            if (typeof overId === 'number') {
                if (e.active.data.current!.tier !== e.over.data.current!.tier) {
                    setTier(e.active.id, e.over.data.current!.tier);
                }
                switchMovie(e.active.id, overId);
                return;
            }
            // We drop a movie in a new tier
            setTier(e.active.id, overId.toString());
        },
        [switchMovie, setTier],
    );

    const handleDragStart = useCallback((e: DragStartEvent) => {
        setActiveMovie(e.active.data.current as Movie);
    }, []);

    const moviesWithoutTier = movies.filter((m) => m.tier === null);
    const canAssignTierToMovies = moviesWithoutTier.length > 0;

    return (
        <>
            {isFetching && <span className="loading loading-spinner text-primary fixed top-4 left-4" />}
            <DndContext onDragEnd={handleDragEnd} onDragStart={handleDragStart}>
                <div className="my-4 grid gap-4" style={{ gridTemplateColumns: canAssignTierToMovies ? '1fr 180px' : '1fr' }}>
                    <div className="relative flex flex-col gap-4">
                        {props.tiers.map((tier) => (
                            <TierRow movies={movies.filter((m) => m.tier === tier.letter)} tier={tier} key={tier.letter} />
                        ))}
                    </div>
                    {canAssignTierToMovies && (
                        <div className="sticky top-0 right-0 block max-h-screen w-[180px] flex-col items-center gap-4 space-y-4 overflow-y-auto rounded-md bg-slate-800 p-4 text-center">
                            {moviesWithoutTier.map((movie) => (
                                <MovieCard movie={movie} key={movie.id} />
                            ))}
                        </div>
                    )}
                </div>
                <DragOverlay>{activeMovie ? <MovieCard movie={activeMovie} overlay /> : null}</DragOverlay>
            </DndContext>
        </>
    );
}

function TierRow({ movies, tier }: { movies: Movie[]; tier: Tier }) {
    const { setNodeRef, isOver } = useDroppable({
        id: tier.letter,
    });
    return (
        <div className={clsx('flex rounded-md', tier.color, isOver && 'outline-2 outline-white')} key={tier.letter}>
            <div className="w-[200px] p-4">
                <div className="text-2xl font-bold">{tier.letter}</div>
                <div className="text-sm font-bold">{tier.title}</div>
                <div className="text-xs">{tier.description}</div>
            </div>
            <SortableContext items={movies} strategy={rectSortingStrategy}>
                <div ref={setNodeRef} className="flex w-full flex-wrap gap-4 p-4">
                    {movies.map((movie) => (
                        <MovieCard movie={movie} key={movie.id} />
                    ))}
                </div>
            </SortableContext>
        </div>
    );
}

function MovieCard({ movie, overlay }: { movie: Movie; overlay?: boolean }) {
    const poster = `https://simkl.in/posters/${movie.poster}_c.webp`;
    const { attributes, listeners, setNodeRef, transform, transition, isDragging } = useSortable({ id: movie.id, data: movie });

    const style = {
        transform: CSS.Transform.toString(transform),
        transition,
    };
    return (
        <button
            className={clsx('block aspect-170/250 overflow-hidden rounded-sm bg-slate-800/30', overlay || isDragging ? 'shadow-lg' : 'shadow-md')}
            ref={setNodeRef}
            style={style}
            {...listeners}
            {...attributes}
        >
            <img src={poster} alt={movie.title} width={170} height={250} className={clsx(isDragging && 'opacity-0', 'w-32')} />
        </button>
    );
}
