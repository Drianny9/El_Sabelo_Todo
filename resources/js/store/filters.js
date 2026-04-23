import { defineStore } from 'pinia';

//Guardamos el estado del buscador del ranking
export const useFiltersStore = defineStore('filters', {
    state: () => ({
        rankingSearch: '',
        rankingSort: 'puntos_desc',
    }),
    actions: {
        clearFilters() {
            this.rankingSearch = '';
            this.rankingSort = 'puntos_desc';
        }
    },
    // Esto lo guarda en LocalStorage (sobrevive aunque cierres la pestaña)
    persist: true 
});
