<template>
    <div>
        <b-tabs content-class="mt-3" justified>
            <b-tab title="Grupos" active @click="inicio_groups">
                <div v-if="listGroups">
                    <b-button variant="success" @click="newGroup">Nuevo grupo</b-button>
                    <hr>
                    <b-table :items="groups" :fields="fieldsGroup" striped responsive="sm">
                        <template slot="index" slot-scope="row">{{ row.index + 1 }}</template>
                        <template slot="teacher" slot-scope="row">
                            {{ row.item.teacher.user.name }} {{ row.item.teacher.user.last_name }}
                        </template>
                        <template slot="details" slot-scope="row">
                            <b-button variant="info" @click="infoGroup(row.item, row.index)">Detalles</b-button>
                        </template>
                        <!-- <template slot="edit" slot-scope="row">
                            <b-button variant="warning" >Editar</b-button>
                        </template> -->
                    </b-table>
                </div>
                <div v-if="formGroup">
                    <form-group-component :registros="teachers" @actListaGroups="updateGroups"></form-group-component>
                </div>
                <div v-if="detailsGroup">
                    <h5><b>{{ group.name }}</b></h5>
                    <h6><b>Profesor:</b> {{ group.teacher }}</h6>
                    <hr>
                    <b-table :items="group.students" :fields="fieldsStudent">
                        <template slot="index" slot-scope="row">{{ row.index + 1 }}</template>
                        <template slot="name" slot-scope="row">
                            {{ row.item.user.name }} {{ row.item.user.last_name }}
                        </template>
                        <template slot="email" slot-scope="row">{{ row.item.user.email }}</template>
                    </b-table>
                </div>
            </b-tab>
            <b-tab title="Profesores" @click="getTeachers">
                <b-button variant="success" v-b-modal.modal-newteacher @click="newTeacher">Nuevo profesor</b-button>
                <hr>
                <b-table :items="teachers" :fields="fieldsTeacher" striped responsive="sm">
                    <template slot="index" slot-scope="row">{{ row.index + 1 }}</template>
                    <template slot="name" slot-scope="row">
                        {{ row.item.name }} {{ row.item.last_name }}
                    </template>
                    <template slot="details" slot-scope="row">
                        <b-button variant="info" v-b-modal.modal-infoTeacher @click="infoTeacher(row.item, row.index)">Información</b-button>
                    </template>
                    <template slot="edit" slot-scope="row">
                        <b-button variant="warning" v-b-modal.modal-editTeacher @click="infoTeacher(row.item, row.index)">Editar</b-button>
                    </template>
                </b-table>
            </b-tab>
        </b-tabs>

        <!-- MODALS TEACHER -->
        <!-- NUEVO PROFESOR -->
        <b-modal id="modal-newteacher" hide-backdrop title="Nuevo profesor">
            <form-teacher-component :teacher="teacher" @actListaTeachers="updateTeachers"></form-teacher-component>
            <div slot="modal-footer"></div>
        </b-modal>
        <!-- EDITAR PROFESOR -->
        <b-modal id="modal-editTeacher" hide-backdrop title="Editar profesor">
            <form-teacher-component :teacher="teacher" @actRegisterTeacher="updateOneTeacher"></form-teacher-component>
            <div slot="modal-footer"></div>
        </b-modal>
        <!-- INFORMACIÓN DEL PROFESOR -->
        <b-modal id="modal-infoTeacher" hide-backdrop :title="`${teacher.name} ${teacher.last_name}`">
            <b-row>
                <b-col sm="4" align="right">
                    <b>Correo:</b> <br>
                    <b>Teléfono:</b> <br>
                    <hr>
                    <b>Username:</b>
                </b-col>
                <b-col>
                    {{ teacher.email }} <br>
                    {{ teacher.telephone }}: <br>
                    <hr>
                    {{ teacher.username }}
                </b-col>
            </b-row>
            <div slot="modal-footer"></div>
        </b-modal>
    </div>
</template>

<script>
    export default {
        name: 'app',
        props: ['grupos'],
        data() {
            return {
                teachers: [],
                groups: this.grupos,
                fieldsTeacher: [
                    { key: 'index', label: '#' },
                    { key: 'name', label: 'Nombre', sortable: true },
                    { key: 'email', label: 'Correo' },
                    { key: 'details', label: '' },
                    { key: 'edit', label: '' }
                ],
                fieldsGroup: [
                    { key: 'index', label: '#' },
                    { key: 'name', label: 'Nombre', sortable: true },
                    { key: 'teacher', label: 'Profesor' },
                    { key: 'details', label: '' },
                    // { key: 'edit', label: '' }
                    { key: '', label: '' }
                ],
                fieldsStudent: [
                    { key: 'index', label: '#' },
                    { key: 'name', label: 'Nombre', sortable: true },
                    { key: 'email', label: 'Correo' }
                ],
                teacher: {
                    id: 0,
                    name: '',
                    last_name: '',
                    email: '',
                    telephone: '',
                    username: ''
                },
                group: {
                    id: 0,
                    name: '',
                    teacher: '',
                    students: []
                },
                position: null,
                listGroups: true,
                detailsGroup: false,
                formGroup: false,
            }
        },
        methods: {
            inicio_groups(){
                this.listGroups = true;
                this.detailsGroup = false;
                this.formGroup = false;
            },
            getTeachers(){
                axios.get('/administrator/get_teachers').then(response => {
                    this.teachers = response.data;
                }).catch(error => {
                    this.toast('b-toaster-top-left', 'danger', 'Ocurrio un problema, vuelve a intentar');
                });
            },
            newTeacher(){
                this.teacher = {
                    id: 0,
                    name: '',
                    last_name: '',
                    email: '',
                    telephone: '',
                    username: ''
                }
            },
            updateGroups(group){
                this.groups.push(group);
                this.listGroups = true;
                this.toast('b-toaster-top-left', 'success', 'El grupo se creo correctamente');
            },
            infoTeacher(teacher, i){
                this.teacher = {
                    id: teacher.id,
                    name: teacher.name,
                    last_name: teacher.last_name,
                    email: teacher.email,
                    telephone: teacher.telephone,
                    username: teacher.username
                };
                this.position = i;
            },
            updateTeachers(teacher){
                this.$bvModal.hide('modal-newteacher');
                this.teachers.push(teacher);
            },
            updateOneTeacher(teacher){
                this.$bvModal.hide('modal-editTeacher');
                this.teachers[this.position].name = teacher.name;
                this.teachers[this.position].last_name = teacher.last_name;
                this.teachers[this.position].email = teacher.email;
            },
            newGroup(){
                axios.get('/administrator/get_teachers').then(response => {
                    this.teachers = response.data;
                    this.listGroups = false;
                    this.formGroup = true;
                }).catch(error => {
                    this.toast('b-toaster-top-left', 'danger', 'Ocurrio un problema, vuelve a intentar');
                });
            },
            infoGroup(group, i){
                axios.get('/administrator/details_group', {params: {group_id: group.id}}).then(response => {
                    this.listGroups = false;
                    this.detailsGroup = true;
                    this.group = {
                        id: response.data.id,
                        name: response.data.name,
                        teacher: response.data.teacher.user.name+' '+response.data.teacher.user.last_name,
                        students: response.data.students
                    };
                }).catch(error => {
                    this.toast('b-toaster-top-left', 'danger', 'Ocurrio un problema, vuelve a intentar');
                });
            },
            toast(toaster, variant, content, append = true) {
                this.$bvToast.toast(content, {
                    title: 'Mensaje',
                    toaster: toaster,
                    variant: variant,
                    solid: true,
                    appendToast: append
                })
            }
        }
    }
</script>