const baseUrl = 'http://127.0.0.1:8000'
const app = Vue.createApp({
    data() {
        return {
            listId: new URLSearchParams(window.location.search).get('list_id'),
            listName: new URLSearchParams(window.location.search).get(
                'list_name'
            ),
            tasks: [],
            priorities: [],
            newTask: {
                task_name: '',
                priority_id: '',
                due_date: '',
            },

            editTaskForm: {
                task_name: '',
                priority_id: '',
                due_date: '',
                is_completed: false,
            },
            sortOption: '',
            tasktoupdate: null,
            showNewTask: false,
            showEditTask: false,
            loading: false,
            errors: false,
            errorMessage: '',
        };
    },

    methods: {
        //COMPLETED CHECK BOX
        async togglemarkascompleted(task) {
            this.tasktoupdate = task.id;

            this.editTaskForm.is_completed = !task.is_completed;
            this.editTaskForm.task_name = task.task_name;
            this.editTaskForm.priority_id = task.priority_id;
            this.editTaskForm.due_date = task.due_date;
            this.updateTask();
        },

        //update selected priority id
        async prioritySelectChanged(val) {
            this.newTask.priority_id = val.target.value;
        },

        //updates the edit task form's priority id
        async editprioritySelectChanged(val) {
            this.editTaskForm.priority_id = val.target.value;
        },

        //GET/DISPLAY ALL THE TASKS
        async fetchTasks() {
            this.loading = true;
            const response = await fetch(`${baseUrl}/api/lists/${this.listId}/tasks`, {
                headers: {
                    'Authorization': `Bearer ${localStorage.getItem('token')}`,
                },
            });

            this.tasks = await response.json();
            this.sortTasks();
            this.loading = false;
        },

        //COMPLETED TASKS AT THE BOTTOM
        sortTasks() {
            this.tasks.sort((a, b) => a.is_completed - b.is_completed);
        },

        //SORT
        sortTasks() {
            this.tasks.sort((a, b) => a.is_completed - b.is_completed);

            if (this.sortOption === 'name-asc') {
                this.tasks.sort((a, b) => a.is_completed - b.is_completed || a.task_name.localeCompare(b.task_name));
            } else if (this.sortOption === 'name-desc') {
                this.tasks.sort((a, b) => a.is_completed - b.is_completed || b.task_name.localeCompare(a.task_name));
            } else if (this.sortOption === 'date-asc') {
                this.tasks.sort((a, b) => a.is_completed - b.is_completed || new Date(a.due_date) - new Date(b.due_date));
            } else if (this.sortOption === 'date-desc') {
                this.tasks.sort((a, b) => a.is_completed - b.is_completed || new Date(b.due_date) - new Date(a.due_date));
            }
        },

        //GET PRIORITIES
        async fetchPriorities() {
            this.loading = true;
            const response = await fetch(`${baseUrl}/api/priorities`, {
                headers: {
                    'Authorization': `Bearer ${localStorage.getItem('token')}`,
                },
            });

            this.priorities = await response.json();
            this.loading = false;
        },

        //ADD A NEW TASK
        async addTask() {
            this.loading = true;
            const response = await fetch(`${baseUrl}/api/lists/tasks/add`, {
                method: 'post',
                headers: {
                    'Content-Type': 'application/json',
                    Authorization: `Bearer ${localStorage.getItem(
                        'token'
                    )}`,
                },
                body: JSON.stringify({
                    task_name: this.newTask.task_name,
                    priority_id: this.newTask.priority_id,
                    due_date: this.newTask.due_date,
                    list_id: this.listId,
                }),
            });

            if (response.ok) {
                this.showNewTask = false;
                this.fetchTasks();
                this.loading = false;
            } else {
                console.error(await response.json());
                this.loading = false;
            }
        },
        
        //UPDATE/EDIT TASK
        async updateTask() {
            this.loading = true;
            const response = await fetch(`${baseUrl}/api/lists/${this.listId}/tasks/${this.tasktoupdate}`, {
                    method: 'put',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${localStorage.getItem('token')}`,
                    },
                    body: JSON.stringify(this.editTaskForm),
                }
            );

            if (response.ok) {
                this.loading = false;
                // console.error(await response.json());
                this.fetchTasks();
                this.showEditTask = false;
            } else {
                this.loading = false;
            }
        },

        //DELETE TASK
        async deleteTask(task) {
            this.loading = true;
            const response = await fetch(`${baseUrl}/api/lists/${this.listId}/tasks/${task.id}`, {
                method: 'delete',
                headers: {
                    'Authorization': `Bearer ${localStorage.getItem('token')}`,
                },
            });

            if (response.ok) {
                this.fetchTasks();
                this.loading = false;
            } else {
                console.error(await response.json());
                this.loading = false;
            }
        },

        // EDIT - DISPLAY TASK INFO THAT WILL BE EDITED
        async editTask(task) {
            this.tasktoupdate = task.id;
            this.showEditTask = true;
            this.editTaskForm.task_name = task.task_name;
            this.editTaskForm.priority_id = task.priority_id;
            this.editTaskForm.due_date = task.due_date;
        },
        async updateTaskForm() {
            const response = await fetch(`${baseUrl}/api/lists/${this.listId}/tasks/${this.editTaskForm.id}`, {
                method: 'put',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${localStorage.getItem('token')}`,
                },
                body: JSON.stringify(this.editTaskForm),
            });

            if (response.ok) {
                this.showEditTask = false;
                this.fetchTasks();
            } else {
                console.error(await response.json());
            }
        },
    },

    mounted() {
        this.fetchTasks();
        this.fetchPriorities();
    },
});

app.mount('#app');
