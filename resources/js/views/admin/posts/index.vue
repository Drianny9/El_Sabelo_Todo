<template>

  <DataTable :value="posts" tableStyle="min-width: 50rem">
    <Column field="id" header="@" sortable=""></Column>
    <Column field="title" header="Titulo"></Column>
    <Column field="content" header="Contenido"></Column>
    <Column field="categories" header="Categoria"></Column>
    <Column field="created_at" header="Creado">
      <template #body="{data}">
        {{ formatDate(data.created_at) }}
      </template>
    </Column>
  </DataTable>

  {{ posts }}
  <div class="w-full space-y-4">
    <div class="flex items-center justify-between">
      <div>
        <h2 class="text-lg font-semibold">Gestión de Posts</h2>
        <p class="text-sm text-gray-600">Lista simple de posts</p>
      </div>
      <div class="flex gap-2">
        <button class="px-3 py-2 text-sm border rounded hover:bg-gray-50 bg-white">
          Actualizar'
        </button>
      </div>
    </div>

    <div class="overflow-x-auto border rounded bg-white shadow-sm">
      <table class="w-full text-sm text-left">
        <thead class="bg-gray-50 text-gray-700 font-medium border-b">
          <tr>
            <th class="px-4 py-3 w-16">ID</th>
            <th class="px-4 py-3">Título</th>
            <th class="px-4 py-3">Categorías</th>
            <th class="px-4 py-3 w-64">Contenido</th>
            <th class="px-4 py-3 whitespace-nowrap">Creado</th>
            <th class="px-4 py-3 text-right">Acciones</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
          <tr v-for="post in posts" class="hover:bg-gray-50">
            <td class="px-4 py-3 text-gray-500">{{ post.id }}</td>

            <td class="px-4 py-3 font-medium text-gray-900">{{ post.title }}</td>
            <td class="px-4 py-3">

            </td>
            <td class="px-4 py-3 text-gray-600">
              <div class="line-clamp-2">{{ post.content }}</div>
            </td>
            <td class="px-4 py-3 whitespace-nowrap text-gray-500">{{ formatDate(post.created_at) }}</td>
            <td class="px-4 py-3 text-right space-x-2">
              <button class="text-blue-600 hover:text-blue-800 font-medium">
                Editar
              </button>
              <button class="text-red-600 hover:text-red-800 font-medium">
                Eliminar
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

</template>

<script setup>
import { onMounted, ref } from 'vue';
import useUtils from "@/composables/utils";
import usePosts from "@/composables/posts";

const { formatDate } = useUtils();
const { posts, getPosts } = usePosts();

onMounted(() => {
  getPosts()
    .then(response =>{
      console.log('Estoy en la vista');
    });
  // axios.get('/api/posts')
  //   .then(response => {
  //     posts.value = response.data;
  //     console.log(response.data);
  //   })
});
</script>