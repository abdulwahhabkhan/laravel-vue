/**
 * Created by PhpStorm.
 * User: Abdul
 * Date: 05/08/2019
 * Time: 22:51
 */

import axios from 'axios';
import filters from "../../config/Filters";
// initial state
const state = {
    sortBy: localStorage.getItem('project_sort_by') || 'id',
    orderBy: localStorage.getItem('project_order_by') || 'asc',
    projects: [],
    append: false,
    summary: {},
    filters: JSON.parse(localStorage.getItem('projects_filters')) || filters.project_list,
    completed: 0,
    pagination: {
        page: 1,
        totalPages: 0,
        totalRows: 0,
        perPage: 0
    }
}

// getters
const getters = {
    getProjects: (state) => { return state.projects },
    getSortBy: (state) => { return state.sortBy },
    getCompleted: (state) => { return state.completed },
    getOrderBy: (state) => { return state.orderBy },
    getSummary: (state) => { return state.summary },
    getFilters: (state) => { return state.filters },
    getProject: (state)=>(id) => { return state.projects.find(project => project.id == id); },
    getPagination: (state) => { return state.pagination },
    getPage: (state) => { return state.pagination.page },
}

// actions
const actions = {
    getProjects: (context, mode )=>{
        let params = Object.keys(context.state.filters).map( key => {
            let val = context.state.filters[key];
            if (Array.isArray(val)){
                let owners = Object.keys(val).map(i => {
                    if (i > 0)
                        return val[i].id;
                    return val[i].id;
                } );
                key += '='+owners;
            }
            else if (typeof(val) === 'object')
                key  += '='+ val.id;
            else if (typeof(val) === 'array'){

            }
            else
                key  += '='+ val;
            return key;
        }).join('&');
        return new Promise(
        (resolve, reject)=> {
            axios.get("/projects?completed="+context.state.completed+"&page=" + context.state.pagination.page + "&orderby=" + context.state.orderBy + "&sortby=" + context.state.sortBy + "&" + params)
                .then(response => {
                    let res = response.data;
                    context.commit('setAppend', mode);
                    context.commit('setProjects', res.data);
                    context.commit('setPagination', {
                        totalPages: res.last_page,
                        totalRows: res.total,
                        page: res.current_page,
                        perPage: res.per_page
                    });
                    resolve(true);
                })
                .catch(error => {
                    reject(error);
                });
        });
    },
    getProject: (context, id) => {
        return new Promise((resolve, reject) => {
            axios.get("/project/" + id)
                .then(response => {
                    context.commit('setProject', response.data);
                    resolve(response);
                })
                .catch(error => {
                    reject(error);
                });
        });
    },
    getSummary: (context, id) => {
        return new Promise((resolve, reject) => {
            axios.get("/project/" + id+ "/summary")
                .then(response => {
                    context.commit('setSummary', response.data.data);
                    resolve(response);
                })
                .catch(error => {
                    reject(error);
                });
        });
    },
    saveProject: (context, data )=>{
        let id = data.id;
        return new Promise((resolve, reject) => {
            axios({
                method: id ? 'put' : 'post',
                url: '/project'+(id ? '/'+id : ''),
                data: data.body
            }).
            then(function (response) {
                resolve(response.data);
            })
                .catch(error => {
                    reject(error);
                });
        });
    },
    completeProject: (context, id) => {
        return new Promise((resolve, reject) => {
            axios({
                method: 'put',
                url: '/project/'+id+'/complete'
            }).
            then(function (response) {
                context.commit('setProject', response.data.data);
                resolve(response);
            })
            .catch(error => {
                reject(error);
            });
        });
    },
    deleteProject: (context, id) => {
        return new Promise((resolve, reject) => {
            axios({
                method: 'delete',
                url: '/project/'+id
            }).
            then(function (response) {
                context.commit('setDelete', id);
                resolve(response);
            })
            .catch(error => {
                reject(error);
            });
        });
    },
}

// mutations
const mutations = {
    setProjects: (state, projects) => {
        if (state.append == true){
            projects.map((val) => {
                state.projects.push(val);
            });
        }
        else
            state.projects = projects
    },
    setPagination: (state, val) => { state.pagination = {...val} },
    setAppend: (state, val) => { state.append = val },
    setPage: (state, val) => { state.pagination.page = val;},
    setSummary: (state, val) => { state.summary = val;},
    setCompleted: (state, val) => { state.completed = val;},
    setSortBy: (state, val) => { state.sortBy = val; localStorage.setItem('project_sort_by', val); },
    setOrderBy: (state, val) => { state.orderBy = val;localStorage.setItem('project_order_by', val); },
    setFilters: (state, val) => { state.filters = {...val}; localStorage.setItem('projects_filters', JSON.stringify(val)); },
    setDelete: (state, id)=> {
        const project= state.projects.find(project => project.id === id);
        project.deleted = true;
    },
    setProject: (state, val) => {
        let id = val.id;
        const project= state.projects.find(project => project.id === id);
        if (project){
            project.name =  val.name;
            project.description =  val.description;
            project.start_date =  val.start_date;
            project.end_date =  val.end_date;
            project.owner = val.owner;
            project.progress = val.progress;
        } else {
            state.projects.push(val);
        }

    },
}

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}
