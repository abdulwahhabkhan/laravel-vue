<template>
    <div v-bind:class="{'hpanel forum-box' : mode == 'update'}" v-if="!id_deleted">
        <div class="panel-body">
            <div class="media">
                <div class="media-author pull-left">
                    <div class="author-info">
                        <avatar :username="author.name" :size="size"></avatar>
                    </div>
                </div>
                <div class="media-body">
                    <form action="" >
                        <div v-show="'update'===mode" v-bind:class="{active: activated}">
                            <div v-if="!activated" v-on:click.self="enableComment()" class="form-control placeholder">add your comments</div>
                            <editor v-model="comment_body" :html="comment_formatted" mode="wysiwyg" ref="commentEditor" height="200px" v-if="activated"></editor>
                            <div v-if="activated">
                                <div class="hr-line-dashed"></div>
                                <button class="btn btn-primary" type="button" v-on:click="saveComment()" :disabled="comment_body.length < 1">Save Comment <i class="fa fa-sync fa-spin" v-if="state.is_saving"></i></button> or
                                <button class="btn w-xs btn-link"  type="button" v-on:click="resetComment()">Cancel</button>
                            </div>

                        </div>
                        <div v-if="'view'===mode">
                            <div class="author-name" v-bind:title="author.name" >{{ author.name }} </div>
                            <div class="formatted-comment" v-html="comment_body"></div>
                        </div>

                    </form>
                </div>
                <div class="media-info pull-right" v-if="'view'===mode">
                    <span v-bind:title="comment.updated_at">{{ comment.updated_at | moment("from", "now") }}</span>
                    <b-dropdown right variant="default" no-caret>
                        <template slot="button-content">
                            <i class="fa fa-ellipsis-v"></i>
                        </template>
                        <a class="dropdown-item" href="javascript:void(0);" @click="editComment()"><i class="fa fa-pen"></i> Edit</a>
                        <a class="dropdown-item" href="javascript:void(0);" @click="deleteComment()" :disabled="state.is_saving"><i class="fa fa-trash"></i> Delete</a>
                    </b-dropdown>
                </div>
            </div>

        </div>
    </div>
</template>

<script>
    import 'tui-editor/dist/tui-editor.css';
    import 'tui-editor/dist/tui-editor-contents.css';
    import 'codemirror/lib/codemirror.css';
    export default {
        name: "Comment",
        props: ['author', 'comment', 'ticket', 'edit'],
        data: function(){
            return {
                state:{
                    is_saving: false
                },
                mode: this.edit ? 'update': 'view',
                comment_body: this.comment.comment,
                comment_formatted : this.comment.comment,
                activated: false,
                size: 38,
                id_deleted: false
            }
        },
        watch: {
            comment: function(val){
                this.comment_body = this.comment.comment;
            },
            comment_body: function () {
                this.comment_formatted = this.$refs.commentEditor.invoke('getHtml');
            }
        },
        mounted(){
            //console.log(this.ticket, this.author_id);
        },
        methods: {
            saveComment: function () {
                this.state.is_saving = true;
                this.$store.dispatch('comments/saveComment', {
                    id: this.comment.id,
                    body: {
                        author_id: this.author.id,
                        ticket_id: this.ticket.id,
                        project_id: this.ticket.project_id,
                        comment: this.$refs.commentEditor.invoke('getHtml')//this.comment_body
                    }
                })
                    .then(response => {
                        this.state.is_saving = false;
                        this.activated = false;
                        if(this.edit=== false)
                            this.mode = 'view';
                        if (!this.comment.id)
                            this.comment_body = this.comment.comment;
                        this.$emit('commentUpdate', response);
                    })
                    .catch(error => {
                        this.state.is_saving = false;
                        let data = error.response.data;
                        this.errors = data.errors;
                        data.type = 'danger';
                        this.$root.$emit('showAlert', data);
                    });
            },
            resetComment: function () {
                this.activated = false;
                if(this.edit=== false)
                    this.mode = 'view';
                this.comment_body = this.comment.comment;
            },
            enableComment(){
                this.activated = true;
            },
            editComment(){
                this.mode = 'update';
                this.activated = true;
                console.info("Edit Called")
            },
            deleteComment(){
                this.state.is_saving = true;
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
                            this.$store.dispatch('comments/deleteComment', this.comment.id)
                                .then(res=>{
                                    this.state.is_saving = false;
                                    this.id_deleted = true;
                                })
                        }
                    }
                );
            }
        },

    }
</script>

<style scoped>
    .placeholder {height: 40px; background: #f1f3f6; cursor:pointer; color:#999;}
    .active textarea {height: 100px; background: transparent;}
</style>
