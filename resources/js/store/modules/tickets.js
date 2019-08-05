/**
 * Created by PhpStorm.
 * User: Abdul
 * Date: 05/08/2019
 * Time: 22:51
 */

import axios from 'axios';

// initial state
const state = {
    sort_by: JSON.parse(localStorage.getItem('ticket_sort_by')) || 'asc',
    tickets: [],
    ticket: {},
    ticket_comments: []
};

// getters
const getters = {
    getTickets: (state) => { return state.tickets },
    getTicket: (state) => { return state.ticket },
    getSortBy: (state) => { return state.sort_by },
    getTicketComments: (state) => { return state.ticket_comments }
};

// actions
const actions = {
    getTicket: (context, id) => {
        axios.get("/ticket/"+id)
            .then(response => {
                context.commit('setTicket', response.data);
            });
    },
    getTicketComments: (context, id) => {
        axios.get("/ticket/"+id+'/comments?sort='+context.state.sort_by)
            .then(response => {
                context.commit('setTicketComments', response.data.data);
            });
    }
};

// mutations
const mutations = {
    setTickets: (state, tickets) => { Object.assign(state.tickets = tickets) },
    setTicket: (state, ticket) => { state.ticket = ticket },
    setTicketComments: (state, comments) => { state.ticket_comments = comments },
    setSortBy: (state, sortby) => { state.sort_by = sortby },
};

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}
