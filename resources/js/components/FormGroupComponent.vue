<template>
    <div>
        <div v-if="selectTeacher">
            <b-row>
                <b-col sm="3"><h6>Seleccionar profesor</h6></b-col>
                <b-col>
                    <b-row>
                        <b-col sm="3"><i class="fa fa-search"></i> Buscar</b-col>
                        <b-col>
                            <b-form-input v-model="queryTeacher" @keyup="searchTeacher"></b-form-input>
                        </b-col>
                    </b-row>
                </b-col>
                <b-col sm="2" align="right">
                    <b-button variant="primary" @click="datosTeacher">Continuar</b-button>
                </b-col>
            </b-row>
            <b-table 
                :items="teachers" 
                :fields="fieldsTeacher"
                selectable
                :select-mode="selectMode"
                selected-variant="success"
                @row-selected="onRowSelected"
                responsive="sm">
                <template slot="index" slot-scope="row">{{ row.index + 1 }}</template>
                <template slot="name" slot-scope="row">
                    {{ row.item.name }} {{ row.item.last_name }}
                </template>
                <template slot="[selected]" slot-scope="{ rowSelected }">
                    <template v-if="rowSelected">
                        <span aria-hidden="true">&check;</span>
                        <span class="sr-only">Selected</span>
                    </template>
                </template>
            </b-table>
        </div>
        <div v-else>
            <b-button variant="primary" @click="saveGroup" :disabled="processing">
                <b-spinner small v-if="processing"></b-spinner> {{ !processing ? 'Guardar' : 'Guardando' }}
            </b-button>
            <hr>
            <b-row>
                <b-col>
                    <b-row>
                        <b-col sm="11"><label><b>Profesor:</b> {{ teacher.name }} {{ teacher.last_name }}</label></b-col>
                        <b-col sm="1" align="right">
                            <b-button variant="outline-warning" @click="changeTeacher"><i class="fa fa-pencil"></i></b-button>
                        </b-col>
                    </b-row>
                </b-col>
                <b-col align="right">
                    <b-button variant="success" v-b-modal.modal-newtStudent>Agregar alumno</b-button>
                </b-col>
            </b-row>
            <hr>
            <b-table :items="students" :fields="fieldsStudent">
                <template slot="index" slot-scope="row">{{ row.index + 1 }}</template>
            </b-table>
        </div>
        <!-- MODALS -->
        <!-- Nuevo estudiante -->
        <b-modal id="modal-newtStudent" hide-backdrop title="Nuevo alumno">
            <b-form @submit.prevent="validateStudent" @reset.prevent="onReset">
                <b-form-group label="Nombre">
                    <b-form-input v-model="form.name" :disabled="processing" required></b-form-input>
                    <div id="error" v-if="errors && errors.name">{{ errors.name[0] }}</div>
                </b-form-group>
                <b-form-group label="Apeliidos">
                    <b-form-input v-model="form.last_name" :disabled="processing" required></b-form-input>
                    <div id="error" v-if="errors && errors.last_name">{{ errors.last_name[0] }}</div>
                </b-form-group>
                <b-form-group label="Correo electronico">
                    <b-form-input v-model="form.email" :disabled="processing" type="email" required></b-form-input>
                    <div id="error" v-if="errors && errors.email">{{ errors.email[0] }}</div>
                </b-form-group>
                <b-form-group label="Telefono">
                    <b-form-input v-model="form.telephone" :disabled="processing" required></b-form-input>
                    <div id="error" v-if="errors && errors.telephone">{{ errors.telephone[0] }}</div>
                </b-form-group>
                <b-button variant="primary" type="submit" :disabled="processing">
                    <b-spinner small v-if="processing"></b-spinner> {{ !processing ? 'Guardar' : 'Guardando' }}
                </b-button>
                <b-button type="reset" variant="danger" :disabled="processing">Borrar datos</b-button>
            </b-form>
            <div slot="modal-footer"></div>
        </b-modal>
    </div>
</template>

<script>
    export default {
        props: ['registros'],
        name: 'app',
        data() {
            return {
                processing: false,
                form: {
                    name: '',
                    last_name: '',
                    email: '',
                    telephone: '',
                },
                errors: {},
                teachers: this.registros,
                fieldsTeacher: [
                    { key: 'index', label: '#' },
                    { key: 'name', label: 'Nombre'},
                    { key: 'email', label: 'Correo' },
                    'selected'
                ],
                fieldsStudent: [
                    { key: 'index', label: '#' },
                    { key: 'name', label: 'Nombre'},
                    { key: 'email', label: 'Correo'},
                    { key: 'telephone', label: 'Teléfono'},
                    { key: 'eliminar', label: ''},
                ],
                selectMode: 'single',
                selectTeacher: true,
                selected: [],
                teacher: {
                    id: 0,
                    name: '',
                    last_name: ''
                },
                students: [],
                queryTeacher: '',
                group: {
                    teacher: {},
                    students: []
                }
            }
        },
        methods: {
            searchTeacher(){
                if(this.queryTeacher.length > 0){
                    axios.get('/administrator/search_teacher', {params: {queryTeacher: this.queryTeacher}}).then(response => {
                        this.teachers = [];
                        this.teachers = response.data;
                    }); 
                }
                else{
                    this.teachers = this.registros;   
                }
            },
            onRowSelected(items){
                this.selected = items;
            },
            datosTeacher(){
                if(this.selected.length > 0){
                    this.teacher = {
                        id: this.selected[0].teacher.id,
                        name: this.selected[0].name,
                        last_name: this.selected[0].last_name
                    }
                    this.selectTeacher = false;
                }
                else{
                    this.toast('b-toaster-top-left', 'warning', 'Seleccionar profesor');
                }
            },
            changeTeacher(){
                this.teacher = {
                    id: 0,
                    name: '',
                    last_name: ''
                };
                this.selectTeacher = true;

            },
            validateStudent(){
                axios.post('/administrator/validate_student', this.form).then(response => {
                        this.errors = {};
                        this.students.unshift(this.form);
                        this.onReset();
                    })
                    .catch(error => {
                        this.processing = false;
                        this.errors = {};
                        if (error.response.status === 422) {
                            this.errors = error.response.data.errors || {};
                        }
                        else{
                            this.toast('b-toaster-top-left', 'danger', 'Ocurrio un problema, vuelve a intentar');
                        }
                    });
            },
            onReset(){
                this.form = {
                    name: '',
                    last_name: '',
                    email: '',
                    telephone: '',
                };
            },
            saveGroup(){
                if(this.students.length > 4){
                    this.group = {
                        teacher: this.teacher,
                        students: this.students
                    };
                    axios.post('/administrator/save_group', this.group).then(response => {
                        this.$emit('actListaGroups', response.data);
                    })
                    .catch(error => {
                        this.processing = false;
                        this.toast('b-toaster-top-left', 'danger', 'Ocurrio un problema, vuelve a intentar');
                    });
                }
                else{
                    this.toast('b-toaster-top-left', 'warning', 'Agregar 5 alumnos como minimo');
                }
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

<style>
    #error {
        color: red;
        font-size: 13px;
    }
</style>