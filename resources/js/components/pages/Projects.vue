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
                                <v-text-field  label="Название" v-model="newProject.title"></v-text-field>
                            </v-col>
                            <v-col cols="12">
                                <v-text-field label="Описание" v-model="newProject.description"></v-text-field>
                            </v-col>
                        </v-row>
                    </v-container>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="green-darken-4" variant="text" @click="createProject()" >
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
                <v-col class="border border-3 px-0" cols="4">
                    Название проекта
                </v-col>
                <v-col class="border border-3 px-0" cols="4">
                    Описание проекта
                </v-col>
                <v-col class="border border-3 px-0" cols="4">
                    Действия
                </v-col>
            </v-row>
            <v-row v-for="project in projects" >
                <v-col class="border border-3 px-0" cols="4">
                    {{ project.title }}
                </v-col>
                <v-col class="border border-3 px-0" cols="4">
                    {{ project.description.slice(0,100) + '...' }}
                </v-col>
                <v-col class="border border-3 px-0" cols="4">
                 <v-btn block color="blue-lighten-1">Редактировать</v-btn>
                 <v-btn block color="orange-lighten-2" class="mt-2">Посмотреть задачи</v-btn>  
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
        createProject(){
            axios.post('/api/projects/create',{
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
        }
    }
}
</script>