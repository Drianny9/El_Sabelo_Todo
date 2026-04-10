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
        if (questions.value.length > 0 && preguntaActualIndex.value < questions.value.length) {
            return questions.value[preguntaActualIndex.value];
        }
        return null; //Devuelve null si no hay preguntas o el indice es incorrecto
        
    });

    //Obtenemos preguntas de la API
    const fetchQuestions = async () => {
            loading.value = true;
            gameover.value = false;
            try {
                //Ruta del fichero routes
                const response = await axios.get('/api/game/questions');
                console.log('Preguntas recibidas de la API:', response.data); //ESTO ES PARA VER SI RECIBE PREGUNTAS EN LA CONSOLA
                questions.value = response.data.data;
                loading.value = false;
            } catch (error) {
                console.error("Error al cargar las preguntas:", error);
                loading.value = false;
            }
    }

    //Guardar puntuación en la base de datos
    const guardarPuntuacion = async () => {
        try {
            // Solo enviamos si hay puntos que sumar, opcional, pero mejor siempre enviarlo para registrar partidas
            await axios.post('/api/game/save-score', {
                puntuacion: puntuacion.value
            });
        } catch (error) {
            console.error("Error al guardar la puntuación:", error);
        }
    };

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
            guardarPuntuacion();
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
        questions,
        currentQuestion,
        preguntaActualIndex,
        fetchQuestions,
        procesarRespuesta,
        reiniciarPartida
    }

}