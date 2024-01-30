<template>
    <v-main class="" style="min-height: 300px;">
        <h1 class="mx-10"> {{ project.title }}</h1>
        <p class="mx-10">{{ project.description }}</p>
        <v-container>

            <v-dialog v-model="dialogCreateTask" persistent width="1024">
                <template v-slot:activator="{ props }">
                    <v-btn block color="orange-lighten-2" v-bind="props">Создать задачу</v-btn>
                </template>
                <v-card>
                    <v-card-title>
                        <span class="text-h5">Создать задачу</span>
                    </v-card-title>
                    <v-card-text>
                        <v-container>
                            <v-row>
                                <v-col cols="12">
                                    <v-text-field label="Название" v-model="newTask.title"></v-text-field>
                                </v-col>
                                <v-col cols="12">
                                    <v-text-field label="Описание" v-model="newTask.description"></v-text-field>
                                </v-col>
                            </v-row>
                        </v-container>
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="green-darken-4" variant="text" @click="createTask()">
                            Сохранить
                        </v-btn>
                        <v-btn color="grey-darken-4" variant="text" @click="dialogCreateTask = false">
                            Закрыть
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
            <br>
            <v-row>
                <v-col block>

                </v-col>
            </v-row>
            <div class="d-flex justify-center">
                <v-sheet class="ma-2 pa-2">
                    <h1>Задачи</h1>
                </v-sheet>
            </div>
            <v-row>
                <v-col>
                    Название задачи
                </v-col>
                <v-col>
                    Описание
                </v-col>
                <v-col>
                    Создал
                </v-col>
                <v-col>
                    Статус
                </v-col>
                <v-col>
                    Время
                </v-col>
                <v-col>
                    Действия
                </v-col>
                <v-col>

                </v-col>
            </v-row>
            <v-row v-for="(task, key) in project.tasks">
                <v-col>
                    {{ task.title }}
                </v-col>
                <v-col>
                    <v-dialog v-model="dialog" persistent width="1024">
                        <template v-slot:activator="{ props }">
                            <v-btn color="indigo-darken-3" v-bind="props">Читать описание</v-btn>
                        </template>
                        <v-card>
                            <v-card-title>
                                <span class="text-h5">{{ task.title }}</span>
                            </v-card-title>
                            <v-card-text>
                                <p>
                                    {{ task.description }}
                                </p>
                            </v-card-text>
                            <v-card-actions>
                                <v-btn color="grey-darken-4" variant="text" @click="dialog = false">
                                    Закрыть
                                </v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-dialog>
                </v-col>
                <v-col>
                    {{ task.user.name }}
                </v-col>
                <v-col>
                    {{ task.isDone ? 'Выполнена' : 'Не выполнена' }}
                </v-col>
                <v-col>
                    {{ showTime(task.start_work_time, key) }}
                    <template v-if="task.isWorking">
                        {{ task.work_time_string }}
                    </template>
                    <template v-if="!task.isWorking">
                        {{ task.work_time_string }}
                    </template>
                </v-col>
                <v-col class="py-0">
                    <div v-if="task.user_working_id != null && !task.isDone">
                        <div v-if="task.user_working_id === user.id">
                            <div v-if="task.isWorking">
                                <svg @click="pauseWorkingTask(task.id, key)" height="40px"
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                    <title>pause-circle-outline</title>
                                    <path
                                        d="M13,16V8H15V16H13M9,16V8H11V16H9M12,2A10,10 0 0,1 22,12A10,10 0 0,1 12,22A10,10 0 0,1 2,12A10,10 0 0,1 12,2M12,4A8,8 0 0,0 4,12A8,8 0 0,0 12,20A8,8 0 0,0 20,12A8,8 0 0,0 12,4Z" />
                                </svg>
                            </div>
                            <div v-if="!task.isWorking">
                                <svg @click="startWorkingTask(task.id, key)" height="40px"
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                    <title>arrow-right-drop-circle</title>
                                    <path
                                        d="M2,12A10,10 0 0,1 12,2A10,10 0 0,1 22,12A10,10 0 0,1 12,22A10,10 0 0,1 2,12M10,17L15,12L10,7V17Z" />
                                </svg>
                            </div>
                        </div>
                        <div v-else-if="task.user_working.id != user.id">
                            {{ 'Задача выполняется пользователем ' + task.user_working.name }}
                        </div>
                    </div>
                    <div v-else-if="task.user_working_id == null">
                        <v-btn @click="setWorkUser(key)" color='green-darken-1'>Начать выполнение</v-btn>
                    </div>
                </v-col>
                <v-col>
                    <v-btn v-if="(task.user_working_id === user.id) && (!task.isWorking) && (!task.isDone)"
                        @click="doneTask(key)" color="green-darken-4">
                        Завершить
                    </v-btn>
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
            dialogCreateTask: false,
            project: {},
            user: {},
            intervalId: null,
            newTask: {
                title: null,
                description: null
            }
        }
    },
    beforeMount() {
        this.getProject(this.$route.params.id);
        this.getUser();
    },
    methods: {
        getProject(id) {
            axios.post('/api/project/get', { id: id }).then(res => {
                this.project = res.data;
            })
        },
        getUser() {
            axios.get('/api/user').then(res => {
                this.user = res.data
            })
        },
        startWorkingTask(task_id, key) {
            axios.post('/api/task/start', { id: task_id }).then(res => {
                this.project.tasks[key].start_working_time = res.start_working_time;
                this.project.tasks[key].isWorking = true;
            })
            this.showTime(this.project.tasks[key].start_work_time, key);
        }
        ,
        pauseWorkingTask(task_id, key) {
            axios.post('/api/task/pause', { id: task_id }).then(res => {
                this.project.tasks[key].start_working_time = res.data.start_working_time;
                this.project.tasks[key].isWorking = false;
                this.project.tasks[key].work_time = res.data.work_time;
            })

        },
        showTime(wtime, key) {
            let id = setInterval(() => {
                if (this.project.tasks[key].isWorking) {
                    this.project.tasks[key].work_time_string = this.getDataString(this.project.tasks[key].work_time);
                    this.project.tasks[key].work_time = this.project.tasks[key].work_time + 1;

                } else {
                    this.project.tasks[key].work_time_string = this.getDataString(this.project.tasks[key].work_time);
                }
                clearInterval(id);
            }, 1000);

        },
        createTask() {
            axios.post('/api/task/create', {
                title: this.newTask.title,
                description: this.newTask.description,
                project_id: this.project.id
            }).then(res => {
                this.project.tasks.push(res.data)
                this.newTask.title = null;
                this.newTask.description = null;
                this.dialogCreateTask = false;
            })

        },
        getDataString(data) {
            let hours = Math.floor(data / 60 / 60);
            let min = Math.floor(data / 60) - (hours * 60);
            let seconds = data % 60;
            let tmin = String(min).length > 1 ? min : '0' + String(min)
            let thours = String(hours).length > 1 ? hours : '0' + String(hours)
            let tseconds = String(seconds).length == 1 ? '0' + String(seconds) : seconds

            return thours + ':' + tmin + ':' + tseconds;
        },
        setWorkUser(key) {
            axios.post('/api/task/setWorkUser', {
                id: this.project.tasks[key].id
            }).then(res => {
                this.project.tasks[key].user_working_id = this.user.id
            });
        },
        doneTask(key) {
            if (confirm('Вы уверены что хотите завершить выполнение задачи?')) {
                axios.post('/api/task/done', { id: this.project.tasks[key].id }).then(res => {
                    this.project.tasks[key].isDone = true;
                })
            }
        },
    }
}
</script>
