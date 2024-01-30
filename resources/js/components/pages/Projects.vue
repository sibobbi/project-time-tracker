<template>
    <v-main class="" style="min-height: 300px;">
        <v-dialog v-model="dialog" persistent width="1024">
            <template v-slot:activator="{ props }">
                <v-btn block class="mt-2" color="indigo-darken-3" v-bind="props">Создать проект</v-btn>
            </template>
            <v-card>
                <v-card-title>
                    <span class="text-h5">Создать проект</span>
                </v-card-title>
                <v-card-text>
                    <v-container>
                        <v-row>
                            <v-col cols="12">
                                <v-text-field label="Название" v-model="newProject.title"></v-text-field>
                            </v-col>
                            <v-col cols="12">
                                <v-text-field label="Описание" v-model="newProject.description"></v-text-field>
                            </v-col>
                        </v-row>
                    </v-container>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="green-darken-4" variant="text" @click="createProject()">
                        Сохранить
                    </v-btn>
                    <v-btn color="grey-darken-4" variant="text" @click="dialog = false">
                        Закрыть
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-container class="border border-2">
            <v-row class="border border-3 px-0">
                <v-col class="border border-3 px-0" cols="3">
                    Название проекта
                </v-col>
                <v-col class="border border-3 px-0" cols="3">
                    Описание проекта
                </v-col>
                <v-col class="border border-3 px-0" cols="3">
                    Общее затраченное время
                </v-col>
                <v-col class="border border-3 px-0" cols="3">
                    Действия
                </v-col>
            </v-row>
            <v-row v-for="(project, key) in projects">
                <v-col class="border border-3 px-0">
                    {{ project.title }}
                </v-col>
                <v-col class="border border-3 px-0">
                    {{ project.description.slice(0, 100) + '...' }}
                </v-col>
                <v-col class="border border-3 px-0">
                    {{ getDataString(project.tasks_sum_work_time) }}
                </v-col>
                <v-col class="border border-3 px-0">
                    <v-dialog v-model="modalEdit" persistent width="1024">
                        <template v-slot:activator="{ props }">
                            <v-btn block color="blue-lighten-1" v-bind="props" @click="modalEditProject(project)">
                                Редактировать</v-btn>
                        </template>
                        <v-card>
                            <v-card-title>
                                <span class="text-h5">Редактировать проект</span>
                            </v-card-title>
                            <v-card-text>
                                <v-container>
                                    <v-row>
                                        <v-col cols="12">
                                            <v-text-field label="Название" v-model="editProject.title"></v-text-field>
                                        </v-col>
                                        <v-col cols="12">
                                            <v-text-field label="Описание" v-model="editProject.description"></v-text-field>
                                        </v-col>
                                    </v-row>
                                </v-container>
                            </v-card-text>
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn color="green-darken-4" variant="text" @click="editProjectSave(key)">
                                    Сохранить
                                </v-btn>
                                <v-btn color="grey-darken-4" variant="text" @click="modalEdit = false">
                                    Закрыть
                                </v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-dialog>
                    <v-btn block color="orange-lighten-2" class="mt-2" @click='checkTasksProject(project.id)'>Посмотреть
                        задачи</v-btn>
                </v-col>
            </v-row>
        </v-container>

    </v-main>
</template>
<script>
export default {
    data() {
        return {
            dialog: false,
            modalEdit: false,
            editProject: {},
            newProject: {
                title: null,
                description: null
            },
            projects: null
        }
    },
    beforeMount() {
        this.getProjects();
    },
    methods: {
        modalEditProject(project) {
            this.editProject = project;
        },
        editProjectSave(key) {
            axios.post('/api/project/edit', {
                id: this.projects[key].id,
                title: this.editProject.title,
                description: this.editProject.description
            }).then(res => {
                this.projects[key] = this.editProject
                this.modalEdit = false;
            })
        },
        createProject() {
            axios.post('/api/projects/create', {
                title: this.newProject.title,
                description: this.newProject.description
            }).then(res => {
                this.projects.push(res.data)
                this.newProject.title = null;
                this.newProject.description = null;
                this.dialog = false;
            })
        },
        getProjects() {
            axios.post('/api/projects/list').then(res => {
                this.projects = res.data;
            })
        },
        checkTasksProject(id) {
            this.$router.push({ name: "project.get", params: { id: id } })
        },
        getDataString(data) {
            data = data == null ? 0 : data;

            let hours = Math.floor(data / 60 / 60);
            let min = Math.floor(data / 60) - (hours * 60);
            let seconds = data % 60;
            let tmin = String(min).length > 1 ? min : '0' + String(min)
            let thours = String(hours).length > 1 ? hours : '0' + String(hours)
            let tseconds = String(seconds).length == 1 ? '0' + String(seconds) : seconds

            return thours + ':' + tmin + ':' + tseconds;
        }
    }
}
</script>