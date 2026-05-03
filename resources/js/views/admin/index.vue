<template>
    <div class="dashboard-container">
        <!-- Page Header Card -->
        <Card class="page-header-card">
            <template #content>
                <div class="page-header-content">
                    <div class="page-header-info">
                        <h1 class="page-title">
                            <i class="pi pi-home page-title-icon"></i>
                            Dashboard
                        </h1>
                        <p class="page-description">
                            Bienvenido al panel de administración. Desde aquí puedes gestionar usuarios, posts, categorías y más.
                        </p>
                    </div>
                </div>
            </template>
        </Card>

        <!-- Stats Cards -->
        <div class="dashboard-stats-grid">
            <Card class="dashboard-stat-card">
                <template #content>
                    <div class="stat-card-content">
                        <div class="stat-card-icon stat-icon-primary">
                            <i class="pi pi-users"></i>
                        </div>
                        <div class="stat-card-info">
                            <p class="stat-card-label">Usuarios</p>
                            <p class="stat-card-value">{{ stats.users || 0 }}</p>
                        </div>
                    </div>
                </template>
            </Card>

            <Card class="dashboard-stat-card">
                <template #content>
                    <div class="stat-card-content">
                        <div class="stat-card-icon stat-icon-success">
                            <i class="pi pi-file"></i>
                        </div>
                        <div class="stat-card-info">
                            <p class="stat-card-label">Posts</p>
                            <p class="stat-card-value">{{ stats.posts || 0 }}</p>
                        </div>
                    </div>
                </template>
            </Card>

            <Card class="dashboard-stat-card">
                <template #content>
                    <div class="stat-card-content">
                        <div class="stat-card-icon stat-icon-success">
                            <i class="pi pi-tags"></i>
                        </div>
                        <div class="stat-card-info">
                            <p class="stat-card-label">Categorías</p>
                            <p class="stat-card-value">{{ stats.categories || 0 }}</p>
                        </div>
                    </div>
                </template>
            </Card>

            <Card class="dashboard-stat-card">
                <template #content>
                    <div class="stat-card-content">
                        <div class="stat-card-icon stat-icon-primary">
                            <i class="pi pi-question-circle"></i>
                        </div>
                        <div class="stat-card-info">
                            <p class="stat-card-label">Preguntas</p>
                            <p class="stat-card-value">{{ stats.questions || 0 }}</p>
                        </div>
                    </div>
                </template>
            </Card>

            <Card class="dashboard-stat-card">
                <template #content>
                    <div class="stat-card-content">
                        <div class="stat-card-icon stat-icon-warning">
                            <i class="pi pi-shield"></i>
                        </div>
                        <div class="stat-card-info">
                            <p class="stat-card-label">Roles</p>
                            <p class="stat-card-value">{{ stats.roles || 0 }}</p>
                        </div>
                    </div>
                </template>
            </Card>

            <Card class="dashboard-stat-card">
                <template #content>
                    <div class="stat-card-content">
                        <div class="stat-card-icon stat-icon-info">
                            <i class="pi pi-table"></i>
                        </div>
                        <div class="stat-card-info">
                            <p class="stat-card-label">Salas</p>
                            <p class="stat-card-value">{{ stats.rooms || 0 }}</p>
                        </div>
                    </div>
                </template>
            </Card>
        </div>

        <!-- Quick Actions Card -->
        <Card class="dashboard-actions-card">
            <template #content>
                <div class="dashboard-actions-content">
                    <h2 class="dashboard-actions-title">
                        <i class="pi pi-bolt"></i>
                        Acciones Rápidas
                    </h2>
                    <div class="dashboard-actions-grid">
                        <router-link
                            to="/admin/users"
                            class="dashboard-action-item"
                        >
                            <div class="dashboard-action-icon stat-icon-primary">
                                <i class="pi pi-users"></i>
                            </div>
                            <div class="dashboard-action-info">
                                <p class="dashboard-action-title">Gestionar Usuarios</p>
                                <p class="dashboard-action-description">Ver y editar usuarios</p>
                            </div>
                            <i class="pi pi-chevron-right dashboard-action-arrow"></i>
                        </router-link>

                        <router-link
                            to="/admin/posts"
                            class="dashboard-action-item"
                        >
                            <div class="dashboard-action-icon stat-icon-success">
                                <i class="pi pi-file"></i>
                            </div>
                            <div class="dashboard-action-info">
                                <p class="dashboard-action-title">Gestionar Posts</p>
                                <p class="dashboard-action-description">Ver y editar posts</p>
                            </div>
                            <i class="pi pi-chevron-right dashboard-action-arrow"></i>
                        </router-link>

                        <router-link
                            to="/admin/categories"
                            class="dashboard-action-item"
                        >
                            <div class="dashboard-action-icon stat-icon-success">
                                <i class="pi pi-tags"></i>
                            </div>
                            <div class="dashboard-action-info">
                                <p class="dashboard-action-title">Gestionar Categorías</p>
                                <p class="dashboard-action-description">Ver y editar categorías</p>
                            </div>
                            <i class="pi pi-chevron-right dashboard-action-arrow"></i>
                        </router-link>

                        <router-link
                            to="/admin/roles"
                            class="dashboard-action-item"
                        >
                            <div class="dashboard-action-icon stat-icon-warning">
                                <i class="pi pi-shield"></i>
                            </div>
                            <div class="dashboard-action-info">
                                <p class="dashboard-action-title">Gestionar Roles</p>
                                <p class="dashboard-action-description">Ver y editar roles</p>
                            </div>
                            <i class="pi pi-chevron-right dashboard-action-arrow"></i>
                        </router-link>

                        <router-link
                            to="/admin/questions"
                            class="dashboard-action-item"
                        >
                            <div class="dashboard-action-icon stat-icon-primary">
                                <i class="pi pi-question-circle"></i>
                            </div>
                            <div class="dashboard-action-info">
                                <p class="dashboard-action-title">Gestionar Preguntas</p>
                                <p class="dashboard-action-description">Ver y editar preguntas</p>
                            </div>
                            <i class="pi pi-chevron-right dashboard-action-arrow"></i>
                        </router-link>

                        <router-link
                            to="/admin/permissions"
                            class="dashboard-action-item"
                        >
                            <div class="dashboard-action-icon stat-icon-danger">
                                <i class="pi pi-key"></i>
                            </div>
                            <div class="dashboard-action-info">
                                <p class="dashboard-action-title">Gestionar Permisos</p>
                                <p class="dashboard-action-description">Ver y editar permisos</p>
                            </div>
                            <i class="pi pi-chevron-right dashboard-action-arrow"></i>
                        </router-link>

                        <router-link
                            to="/admin/rooms"
                            class="dashboard-action-item"
                        >
                            <div class="dashboard-action-icon stat-icon-info">
                                <i class="pi pi-table"></i>
                            </div>
                            <div class="dashboard-action-info">
                                <p class="dashboard-action-title">Gestionar Salas</p>
                                <p class="dashboard-action-description">Ver y eliminar salas de juego</p>
                            </div>
                            <i class="pi pi-chevron-right dashboard-action-arrow"></i>
                        </router-link>
                    </div>
                </div>
            </template>
        </Card>
    </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import useUsers from "../../composables/users";
import usePosts from "../../composables/posts";
import useCategories from "../../composables/categories";
import useRoles from "../../composables/roles";
import useQuestions from "../../composables/questions";
import useAdminRooms from "../../composables/adminRooms";

const stats = ref({
    users: 0,
    posts: 0,
    categories: 0,
    roles: 0,
    questions: 0,
    rooms: 0
});

const { users, getUsers } = useUsers();
const { posts, getPosts } = usePosts();
const { categories, getCategories } = useCategories();
const { roles, getRoles } = useRoles();
const { questionList, getQuestionList } = useQuestions();
const { adminRooms, getAdminRooms } = useAdminRooms();

const loadStats = async () => {
    try {
        await Promise.all([
            getUsers(),
            getPosts(),
            getCategories(),
            getRoles(),
            getQuestionList(),
            getAdminRooms()
        ]);
        
        stats.value = {
            users: users.value?.total || users.value?.data?.length || 0,
            posts: posts.value?.total || posts.value?.data?.length || 0,
            categories: categories.value?.total || categories.value?.data?.length || 0,
            roles: roles.value?.total || roles.value?.data?.length || 0,
            questions: questionList.value?.length || 0,
            rooms: adminRooms.value?.total || adminRooms.value?.data?.length || 0
        };
    } catch (error) {
        console.error('Error loading stats:', error);
    }
};

onMounted(() => {
    loadStats();
});
</script>

<style scoped>
.stat-icon-info {
    background: linear-gradient(135deg, #0ea5e9, #38bdf8);
}

.dashboard-container {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.dashboard-stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
}

.dashboard-stat-card {
    border-radius: 0.75rem;
    box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
    border: 1px solid rgba(0, 0, 0, 0.05);
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}

.dashboard-stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
}

.dashboard-actions-card {
    border-radius: 0.75rem;
    box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
    border: 1px solid rgba(0, 0, 0, 0.05);
}

.dashboard-actions-content {
    padding: 0.5rem;
}

.dashboard-actions-title {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    font-size: 1.25rem;
    font-weight: 700;
    line-height: 1.75rem;
    letter-spacing: -0.02em;
    margin-bottom: 1.5rem;
}

.dashboard-actions-title i {
    font-size: 1.5rem;
    opacity: 0.9;
}

.dashboard-actions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1rem;
}

.dashboard-action-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    border-radius: 0.75rem;
    border: 1px solid rgba(0, 0, 0, 0.08);
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    text-decoration: none;
    cursor: pointer;
}

.dashboard-action-item:hover {
    transform: translateX(4px);
    box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.1);
    border-color: rgba(0, 0, 0, 0.12);
}

.dashboard-action-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 2.5rem;
    height: 2.5rem;
    border-radius: 0.5rem;
    font-size: 1.25rem;
    flex-shrink: 0;
    color: white;
    box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.1);
}

.dashboard-action-info {
    flex: 1;
    min-width: 0;
}

.dashboard-action-title {
    font-size: 1rem;
    font-weight: 600;
    line-height: 1.5rem;
    margin-bottom: 0.25rem;
    letter-spacing: -0.01em;
}

.dashboard-action-description {
    font-size: 0.875rem;
    opacity: 0.7;
    line-height: 1.25rem;
}

.dashboard-action-arrow {
    font-size: 1.25rem;
    opacity: 0.5;
    transition: all 0.2s ease-in-out;
    flex-shrink: 0;
}

.dashboard-action-item:hover .dashboard-action-arrow {
    opacity: 1;
    transform: translateX(4px);
}

@media (max-width: 640px) {
    .dashboard-stats-grid {
        grid-template-columns: 1fr;
    }

    .dashboard-actions-grid {
        grid-template-columns: 1fr;
    }
}
</style>
