import { useState, useMemo, useEffect } from 'react';

type Sortable = string | number;

export function useTable<T>(data: T[], itemsPerPage: number = 10) {
  const [currentPage, setCurrentPage] = useState(1);
  const [sortConfig, setSortConfig] = useState<{ key: keyof T; direction: 'asc' | 'desc' } | null>(null);

  const requestSort = (key: keyof T) => {
    let direction: 'asc' | 'desc' = 'asc';

    if (sortConfig && sortConfig.key === key && sortConfig.direction === 'asc') {
      direction = 'desc';
    }

    setSortConfig({ key, direction });
    setCurrentPage(1);
  };

  const sortedData = useMemo(() => {
    const sortableItems = [...data];

    if (!sortConfig) return sortableItems;

    return sortableItems.sort((a, b) => {
      const valA = a[sortConfig.key] as Sortable;
      const valB = b[sortConfig.key] as Sortable;

      if (valA == null) return 1;
      if (valB == null) return -1;

      if (typeof valA === 'string' && typeof valB === 'string') {
        return sortConfig.direction === 'asc'
          ? valA.localeCompare(valB)
          : valB.localeCompare(valA);
      }

      return sortConfig.direction === 'asc'
        ? valA < valB ? -1 : 1
        : valA > valB ? -1 : 1;
    });

  }, [data, sortConfig]);

  const totalItems = sortedData.length;

  const totalPages = useMemo(() => {
    return Math.ceil(totalItems / itemsPerPage);
  }, [totalItems, itemsPerPage]);

  useEffect(() => {
    if (currentPage > totalPages) {
      setCurrentPage(1);
    }
  }, [itemsPerPage, totalPages]);

  const currentData = useMemo(() => {
    const start = (currentPage - 1) * itemsPerPage;
    return sortedData.slice(start, start + itemsPerPage);
  }, [sortedData, currentPage, itemsPerPage]);

  return {
    currentData,
    currentPage,
    setCurrentPage,
    totalPages,
    requestSort,
    sortConfig,
    totalItems,
    itemsPerPage,
  };
}