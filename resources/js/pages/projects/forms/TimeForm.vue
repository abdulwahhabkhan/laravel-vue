<template>
    <b-modal id="time-form" size="lg" top  ref="time_form" :ok-title="btn_ok_label" :title="modal_title">
        <template v-slot:modal-footer="{ ok, cancel, hide }">
            <button type="button" class="btn btn-secondary mr-auto" @click="cancel()">Cancel</button>
            <button type="button" class="btn btn-danger mr-auto" v-if="time_log" @click="deleteTimeLog"><i class="fa fa-times"></i> Delete time Entry</button>
            <button type="button" class="btn btn-success" @click="saveTimeLog">{{btn_ok_label}}  <i class="fa fa-sync fa-spin" v-if="state.is_saving"></i></button>
        </template>
        <div class="modal-info-bar" v-if="ticket">
            <span class="modal-info-bar-label">Ticket</span>
            <span class="modal-info-bar-content">{{ ticket.title }}</span>
        </div>
        <div class="row mt-3">
            <div class="col-md-12">
                <form method="get" class="form-horizontal">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="flex-row">
                                <div class="form-group">
                                    <label>Who</label>
                                    <div class="who-select">
                                        <v-select
                                            v-model="timeEntry.user"
                                            label="name"
                                            :options="users"
                                            :clearable="false"
                                        ></v-select>
                                    </div>

                                </div>
                                <div class="">
                                    <div class="form-group">
                                        <label class="control-label">Date</label>
                                        <div class="input-group">
                                            <date-picker v-model="start_date" class="form-control form-control-sm date-input"  :config="configs.start" ref="startDate"></date-picker>
                                            <div class="input-group-append">
                                                <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="">
                                    <div class="form-group">
                                        <label class="control-label">Start Time</label>
                                        <div class="input-group">
                                            <date-picker v-model="start_time" @dp-change="startTimeUpdate" class="form-control form-control-sm time-input" :config="configs.start_time" ref="startDate"></date-picker>
                                            <div class="input-group-append">
                                                <span class="input-group-text"><i class="fa fa-clock"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="">
                                    <div class="form-group">
                                        <label class="control-label">End Time</label>
                                        <div class="input-group">
                                            <date-picker v-model="end_time" @dp-change="endTimeUpdate" class="form-control form-control-sm time-input" :config="configs.end_time" ref="startDate"></date-picker>
                                            <div class="input-group-append">
                                                <span class="input-group-text"><i class="fa fa-clock"></i></span>
                                            </div>
                                        </div>
                                        <small v-if="show_end_date">{{ end_date }}</small>
                                    </div>
                                </div>
                                <div class="">
                                    <div class="">
                                        <label class="control-label">Duration</label>
                                        <div class="form-content">
                                            <div class="form-group">
                                                <input type="text" name="title" v-model="hours" class="form-control form-control-sm hour-input">
                                                <small>hours</small>
                                            </div>
                                            <div class="form-group">
                                                <input type="text" name="title" v-model="minutes" class="form-control form-control-sm minute-input">
                                                <small>minutes</small>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <b-tabs nav-wrapper-class="nav-htabs">
                            <b-tab title="Description" :active="tab == 'description'">
                                <div class="form-group">
                                    <label  for="timelog_desc">
                                        Provide a Description
                                    </label>

                                    <div class="">
                                        <textarea tabindex="2" v-model="timeEntry.description" class="form-control" id="timelog_desc"></textarea>
                                    </div>
                                </div>
                            </b-tab>
                            <b-tab title="Task"  :active="tab == 'task'">

                            </b-tab>
                        </b-tabs>
                    </div>
                </form>
            </div>

        </div>
    </b-modal>
</template>

<script>
    import { cloneDeep } from 'lodash';
    import moment from 'moment';

    export default {
        name: "time-form",
        props: {
            'ticket': {type:Object, default: () => {}},
            'project_id': {type:Number, required: true},
            'id': {type: Number, default: 0},
            'add' : {type: Boolean, default: false},
            'tab': {default: 'description'},
            'time_log': {type: Object, default: () => {}}
        },
        data(){
            return {
                myRoute : {},
                project: {
                    'id': '', 'name':'', 'description':'', 'owner': {}, 'start_date': '', 'end_date': ''
                },
                state:{
                    is_saving: false
                },
                timeEntry: {
                    user : {'id': this.$user.id, 'name': this.$user.name},
                    start_date: this.$moment().format(this.$settings.FORMDATEFROMAT),
                    start_time: this.$moment().format(this.$settings.TIMEFROMAT),
                    hours: 0,
                    minutes: 0,
                    description: '',
                    id: 0
                },
                configs: {
                    timePicker: {
                        format: 'LT',
                        useCurrent: false
                    },
                    start: {
                        format: this.$settings.FORMDATEFROMAT,
                        useCurrent: false,
                        showClear: true,
                        showClose: true,
                        //minDate: new Date(),
                        //maxDate: false
                    },
                    start_time: {
                        format: 'hh:mm a',
                        useCurrent: false,
                        showClear: false,
                        showClose: true,
                        //minDate: new Date()
                    },
                    end_time: {
                        format: 'hh:mm a',
                        useCurrent: false,
                        showClear: false,
                        showClose: true,
                        minDate: this.$moment().format(this.$settings.FROMFUllFROMAT),
                    }
                }
            }
        },
        watch:{

            start_date: function(val){
                this.end_date =  this.$moment(this.timeEntry.start_date+ ''+ this.timeEntry.start_time, this.$settings.FROMFUllFROMAT).add(this.timeEntry.hours, 'hours').add(this.timeEntry.minutes, 'minutes').format(this.$settings.FORMDATEFROMAT);
            },
            end_time: function(){

            }
        },
        computed: {
            modal_title: function(){
                return this.time_log ? 'Edit Logged Time': 'Log Time';
            },
            btn_ok_label: function(){
                return this.time_log ? 'Save Changes': 'Log Time';
            },
            show_end_date: function(){
                let s_date = this.$moment(this.start_date, this.$settings.FORMDATEFROMAT);
                let e_date = this.$moment(this.end_date, this.$settings.FORMDATEFROMAT);
                return !s_date.isSame(e_date, 'day');
            },
            start_date:{
                get: function(){
                    return this.timeEntry.start_date;
                },
                set: function (val) {
                    this.timeEntry.start_date= val;
                }
            },
            start_time:{
                get: function(){
                    return this.timeEntry.start_time;
                },
                set: function (val) {
                    //this.timeEntry.start_time = val;
                }
            } ,
            end_time: {
                get: function(){
                    return this.$moment(this.timeEntry.start_time, this.$settings.TIMEFROMAT).add(this.timeEntry.hours, 'hours').add(this.timeEntry.minutes, 'minutes').format(this.$settings.TIMEFROMAT);
                },
                set: function (val) {
                    //
                }
            },
            end_date: {
                get: function(){
                    return this.$moment(this.timeEntry.start_date+ ' '+ this.timeEntry.start_time, this.$settings.FROMFUllFROMAT).add(this.hours, 'hours').add(this.minutes, 'minutes').format(this.$settings.FORMDATEFROMAT)
                },
                set: function (val) {
                    //this.timeEntry.end_time=  val;
                }
            },
            hours: {
                get: function(){
                    return this.timeEntry.hours;
                },
                set: function (val) {
                    this.timeEntry.hours=  val > 0? val : 0;
                }
            },
            minutes: {
                get: function(){
                    return this.timeEntry.minutes;
                },
                set: function (val) {
                    this.timeEntry.minutes=  val > 0 ? val: 0;
                }
            },
            users(){
                return this.$store.getters['users/getUsersList'];
            }
        },
        components: {

        },
        mounted(){
            /*if(this.id)
                this.$store.dispatch('projects/getProject', this.id)
                    .then(response => {
                        this.project = this.$store.getters['projects/getProject'](this.id);
                    })*/
            if(this.time_log){
                this.timeEntry = Object.assign({}, this.time_log);
                if(typeof this.timeEntry.user == "undefined"){
                  this.timeEntry.user = { id : this.time_log.user_id, name : this.time_log.user_name }
                }
            }
            this.timeEntry.end_date = this.timeEntry.start_date;
            this.$store.dispatch('users/getUsersList');
            this.$refs['time_form'].show();
        },
        methods:{
            onStartChange(e) {
                this.$set(this.configs.end, 'minDate', e.date || null);
            },
            onEndChange(e) {
                this.$set(this.configs.start, 'maxDate', e.date || null);
            },
            get_start_date: function(date, time){
                let dt = date;
                if (time !== undefined)
                    dt += ' '+ time;
                return this.$moment(dt, this.$settings.FROMFUllFROMAT);
            },
            endTimeUpdate: function(e){
                let val = e.date.format(this.$settings.TIMEFROMAT);
                let s_date = this.$moment(this.start_date+ ' '+ this.start_time, this.$settings.FORMDATEFROMAT);
                let e_date = this.$moment(this.end_date+ ' '+ this.end_time, this.$settings.FORMDATEFROMAT);
                //console.log( e_date.diff(s_date, 'minutes'));
                this.getDuration(this.get_start_date(this.start_date, this.start_time), this.get_end_date(this.end_date, val)).then(res => {
                    this.hours = res.hours;
                    this.minutes = res.minutes;
                });
            },
            startTimeUpdate: function(e){
                let val = e.date.format(this.$settings.TIMEFROMAT);
                console.log('start time update', val);
                this.getDuration(this.get_start_date(this.start_date, val), this.get_end_date(this.end_date,  this.end_time)).then(res => {
                    console.log(res);
                    this.hours = res.hours;
                    this.minutes = res.minutes;
                    this.timeEntry.start_time = val;
                });
            },
            get_end_date: function(date, time){
                let dt = date;
                if (time !== undefined)
                    dt += ' '+ time;
                return this.$moment(dt, this.$settings.FROMFUllFROMAT);
            },
            getDuration: function(s_date, e_date){
                return new Promise((resolve, reject) => {
                    let hours = e_date.diff(s_date, 'hours');
                    let mins = e_date.diff(s_date.add(hours, 'hours'), 'minutes');
                    resolve({'hours': hours, 'minutes': mins});
                });

            },
            saveTimeLog:function () {
                this.state.is_saving = true;
                this.$store.dispatch('timelog/saveTime', {
                    id: this.timeEntry.id,
                    body: {
                        id: this.timeEntry.id,
                        description: this.timeEntry.description,
                        date: this.timeEntry.start_date,
                        time: this.timeEntry.start_time,
                        hours: this.timeEntry.hours,
                        minutes: this.timeEntry.minutes,
                        ticket_id: this.id,
                        project_id: this.project_id,
                        user_id: this.timeEntry.user? this.timeEntry.user.id : '',
                    }
                })
                    .then(response => {
                        this.state.is_saving = false;
                        this.$root.$emit('timeUpdate', response);
                        this.$toast.success('Time Log updated successfully', "Success", {
                            timout: 3000,
                            position: 'bottomRight'
                        });
                        this.$refs.time_form.hide();
                    })
                    .catch(error => {
                        this.state.is_saving = false;
                        let data = error.response.data;
                        this.errors = data.errors;
                        data.type = 'danger';
                        this.$root.$emit('showAlert', data);
                    });
            },
            deleteTimeLog: function () {
                this.$swal(
                    {
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.value) {
                            this.$store.dispatch('timelog/deleteTime', this.time_log.id)
                                .then(res=>{
                                    this.$root.$emit('timeUpdate', this.timeEntry);
                                    this.$toast.success('Time log deleted successfully', "Success", {
                                        timout: 1000,
                                        position: 'bottomRight'
                                    });
                                    this.$nextTick(() => {
                                        this.$refs.time_form.hide();
                                    })
                                })
                                .catch(exp=>{
                                    this.$emit('handle-exception', exp);
                                })
                        }
                    }
                );
            }
        },
        beforeRouteUpdate (to, from, next) {
            next();
        },

    }
</script>

<style scoped>

</style>
