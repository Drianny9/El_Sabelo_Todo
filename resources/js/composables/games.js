import { ref, computed } from "vue";
import axios from 'axios';

export default function useGame(roomCode = null){ //Aceptamos un roomCode opcional
    //Estado de la partida
    const questions = ref([]); //Lista que almacena  las preguntas de la partida
    const preguntaActualIndex = ref(0); //Para saber que pregunta mostrar
    const puntuacion = ref(0);
    const gameover = ref(false); //Para saber si la partida ha terminado
    const loading = ref(false); //Para mostrar un circulo de carga

    //DEVOLVEMOS LA PREGUNTA ACTUAL
    const currentQuestion = computed(() => {
        if (questions.value.length > 0 && preguntaActualIndex.value < questions.value.length) {
            return questions.value[preguntaActualIndex.value];
        }
        return null; //Devuelve null si no hay preguntas o el indice es incorrecto
        
    });

    //Obtenemos preguntas de la API
    const fetchQuestions = async () => {
            loading.value = true;
            gameover.value = false;
            puntuacion.value = 0;
            preguntaActualIndex.value = 0;
            questions.value = [];

            try {
                let url;
                if (roomCode) {
                    url = `/api/rooms/${roomCode}/questions`;
                } else {
                    url = '/api/game/questions';
                }
                
                const response = await axios.get(url);

                // Ambos endpoints ahora devuelven una estructura con 'data' gracias a QuestionResource
                questions.value = response.data.data;
                
                console.log('Preguntas recibidas de la API:', questions.value);
                loading.value = false;
            } catch (error) {
                console.error("Error al cargar las preguntas:", error);
                loading.value = false;
            }
    }

    //Procesar la respuesta del usuario
    const procesarRespuesta = (opcionSeleccionada) => {
        if (opcionSeleccionada.es_correcta) {
            puntuacion.value++;
        }

        //Avanza a la siguiente pregunta o termina el juego
        if (preguntaActualIndex.value < questions.value.length - 1) {
            preguntaActualIndex.value++;
        } else {
            gameover.value = true;
        }
        
        //Si es una partida 1vs1, enviamos la puntuación al servidor
        if (gameover.value && roomCode) {
            submitScore();
        }
    }

    //Nueva función para enviar la puntuación en modo 1vs1
    const submitScore = async () => {
        try {
            await axios.post(`/api/rooms/${roomCode}/submit`, { score: puntuacion.value });
            console.log('Puntuación enviada con éxito');
        } catch (error) {
            console.error("Error al enviar la puntuación:", error);
        }
    };

    //Reiniciar la partida
    const reiniciarPartida = () => {
        fetchQuestions(); //Simplemente pide un nuevo set de preguntas
    }

    //Lo que el componente de vue podra usar
    return {
        loading,
        gameover,
        puntuacion,
        questions,
        currentQuestion,
        preguntaActualIndex,
        fetchQuestions,
        procesarRespuesta,
        reiniciarPartida,
    }

}