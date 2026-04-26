import { ref, computed } from "vue";
import axios from 'axios';
import { authStore } from "@/store/auth";

export default function useGame(roomCode = null){ //Aceptamos un roomCode opcional
    //Estado de la partida
    const auth = authStore();//Inicializamos el store de auth
    const questions = ref([]); //Lista que almacena  las preguntas de la partida
    const preguntaActualIndex = ref(0); //Para saber que pregunta mostrar
    const puntuacion = ref(0);
    const gameover = ref(false); //Para saber si la partida ha terminado
    const loading = ref(false); //Para mostrar un circulo de carga
    const nuevosLogros = ref([]); //Logros recién desbloqueados en esta partida

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

    //Guardar puntuación en la base de datos (ranking individual)
    const guardarPuntuacion = async () => {
        try {
            const response = await axios.post('/api/game/save-score', {
                puntuacion: puntuacion.value
            });

            //Sincronizamos la puntuación del store con el total real del backend
            //Esto incluye puntos de la partida + puntos bonus de logros desbloqueados
            if (response.data.puntuacion !== undefined) {
                auth.user.puntuacion = response.data.puntuacion;
            }

            //Si el backend devuelve logros nuevos, los almacenamos para mostrarlos en la vista
            if (response.data.nuevos_logros && response.data.nuevos_logros.length > 0) {
                nuevosLogros.value = response.data.nuevos_logros;
                console.log('¡Logros desbloqueados!', nuevosLogros.value);
            }
        } catch (error) {
            console.error("Error al guardar la puntuación:", error);
        }
    };

    //Procesar la respuesta del usuario (sólo puntuar, no avanzar)
    const procesarRespuesta = (opcionSeleccionada) => {
        if (opcionSeleccionada.es_correcta) {
            puntuacion.value++;
        }
    }

    //Avanza a la siguiente pregunta o termina el juego al pulsar el botón
    const avanzarPregunta = () => {
        if (preguntaActualIndex.value < questions.value.length - 1) {
            preguntaActualIndex.value++;
        } else {
            gameover.value = true;
            //Si es una partida 1vs1, enviamos la puntuación al servidor de la sala
            if (roomCode) {
                submitScore();
            } else {
                //Si es una partida normal individual, guardamos en el Ranking
                guardarPuntuacion();
            }
        }
    }

    //Nueva función para enviar la puntuación en modo 1vs1
    const submitScore = async () => {
        console.log('Initiating submitScore for room:', roomCode, 'with score:', puntuacion.value);
        try {
            const response = await axios.post(`/api/rooms/${roomCode}/submit`, { score: puntuacion.value });
            console.log('Puntuación enviada con éxito:', response.data);
        } catch (error) {
            console.error("Error al enviar la puntuación:", error.response ? error.response.data : error);
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
        nuevosLogros,
        fetchQuestions,
        procesarRespuesta,
        avanzarPregunta,
        reiniciarPartida,
    }

}