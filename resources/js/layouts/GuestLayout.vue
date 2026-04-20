<template>
    <div class="min-h-screen flex flex-col">
        <Navbar/>
        <main class="flex-grow">
            <router-view></router-view>
        </main>
        <Footer/>
    </div>
</template>
<script setup>
import Navbar from './LandingNavbar.vue'
import Footer from './FooterLayout.vue'

</script>
