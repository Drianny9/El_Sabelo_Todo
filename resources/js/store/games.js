import { defineStore } from 'pinia';

//Sirve para que no se rompa una partida 1vs1 para que el ordenador no olvide en que sala estabas si haces F5 sin querer
export const useGameStore = defineStore('game', {
    state: () => ({
        activeRoomCode: null,
        isRoomCreator: false,
    }),
    actions: {
        setRoom(code, isCreator) {
            this.activeRoomCode = code;
            this.isRoomCreator = isCreator;
        },
        clearRoom() {
            this.activeRoomCode = null;
            this.isRoomCreator = false;
        }
    },
    // Esto hace que los datos no se borren si se pulsa F5 (guardado en SessionStorage)
    persist: {
        storage: sessionStorage,
    }
});
