import { ref, computed } from "vue";
import axios from 'axios';

export default function useGame(){
    //Estado de la partida
    const questions = ref([]); //Lista que almacena  las preguntas de la partida
    const preguntaActualIndex = ref(0); //Para saber que pregunta mostrar
    const puntuacion = ref(0);
    const gameover = ref(false); //Para saber si la partida ha terminado
    const loading = ref(false); //Para mostrar un circulo de carga

    //DEVOLVEMOS LA PREGUNTA ACTUAL
    const currentQuestion = computed(() => {
        return questions.value[preguntaActualIndex.value]
    });

    //Obtenemos preguntas de la API
    const fetchQuestions = async () => {
            loading.value = true;
            gameover.value = false;
            try {
                //Ruta del fichero routes
                const response = await axios.get('/api/game/questions');
                questions.value = response.data;
            } catch (error) {
                console.error("Error al cargar las preguntas:", error);
            } finally {
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
    }

    //Reiniciar la partida
    const reiniciarPartida = () => {
        questions.value = [];
        preguntaActualIndex.value = 0;
        puntuacion.value = 0;
        gameover.value = false;
        fetchQuestions(); //Pide un nuevo set de preguntas
    }

    //Lo que el componente de vue podra usar
    return {
        loading,
        gameover,
        puntuacion,
        fetchQuestions,
        procesarRespuesta,
        reiniciarPartida
    }

}