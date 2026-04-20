<template>
    <!-- ===== FOOTER ===== -->
    <footer class="relative bg-[#111827] pt-14 pb-0 overflow-hidden">
        <!-- Monedas decorativas -->
        <img src="/images/Home/Monedas_izquierda.webp" alt=""
            class="absolute bottom-0 left-0 w-52 md:w-72 select-none pointer-events-none" />
        <img src="/images/Home/Monedas_derecha.webp" alt=""
            class="absolute bottom-0 right-0 w-52 md:w-72 select-none pointer-events-none" />
        <img src="/images/Home/Medio_monedas.webp" alt=""
            class="absolute bottom-0 left-1/2 -translate-x-1/2 w-36 md:w-52 select-none pointer-events-none" />

        <div class="relative z-10 max-w-4xl mx-auto px-8 grid grid-cols-1 md:grid-cols-3 gap-10 pb-40">
            <!-- Col 1: Marca -->
            <div>
                <img src="/images/Logo_ElSabeloTodo.svg" alt="El Sabelotodo" class="h-9 mb-4" />
                <p class="text-gray-400 text-sm leading-relaxed">Pon a prueba tu sabiduría</p>
                <p class="text-gray-600 text-xs mt-4">© 2026 EL SABELOTODO</p>
            </div>

            <!-- Col 2: Legal & juego -->
            <div>
                <h4 class="text-white font-bold text-xs uppercase tracking-widest mb-5">Legal & Juego</h4>
                <ul class="space-y-2.5 text-gray-400 text-sm">
                    <li><a href="#" class="hover:text-white transition-colors">Reglas del Juego</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Cómo Jugar</a></li>
                    <li><a href="#" @click.prevent="goToRanking" class="hover:text-white transition-colors">Ranking
                            global</a></li>
                    <li class="pt-2"><a href="#" class="hover:text-white transition-colors">Política Privacidad</a>
                    </li>
                    <li><a href="#" class="hover:text-white transition-colors">Términos de Uso</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Cookies</a></li>
                </ul>
            </div>

            <!-- Col 3: Comunidad -->
            <div>
                <h4 class="text-white font-bold text-xs uppercase tracking-widest mb-5">Comunidad</h4>
                <div class="flex gap-3 mb-5">
                    <a href="#"
                        class="w-9 h-9 bg-gray-700 hover:bg-purple-600 rounded-lg flex items-center justify-center transition-colors">
                        <i class="pi pi-instagram text-white"></i>
                    </a>
                    <a href="#"
                        class="w-9 h-9 bg-gray-700 hover:bg-purple-600 rounded-lg flex items-center justify-center transition-colors">
                        <i class="pi pi-twitter text-white"></i>
                    </a>
                    <a href="#"
                        class="w-9 h-9 bg-gray-700 hover:bg-purple-600 rounded-lg flex items-center justify-center transition-colors">
                        <i class="pi pi-discord text-white"></i>
                    </a>
                </div>
                <p class="text-gray-400 text-sm">¿Necesitas ayuda?</p>
            </div>
        </div>
    </footer>
</template>

<script setup>
import { useRouter } from 'vue-router';

const router = useRouter();

const goToRanking = () => {
    router.push({ name: 'game.individual.ranking' });
};
</script>
