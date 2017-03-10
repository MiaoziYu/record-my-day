<template>
    <div>
        <h1>Records</h1>

        <div>
            <input type="text" placeholder="name" v-model="recordToCreate.name">
            <select v-model="recordToCreate.score">
                <option value="-20">very negative</option>
                <option value="-10">negative</option>
                <option value="0">neutral</option>
                <option value="10">positive</option>
                <option value="20">very positive</option>
            </select>
            <input type="text" placeholder="duration" v-model="recordToCreate.duration">
            <button v-on:click="createRecord">submit</button>
        </div>

        <ul class="record-list">
            <li class="record" v-for="record in records">
                <div>
                    <span class="text" v-on:dblclick="textToInput()">{{ record.name }}</span>
                    <input class="input"
                           :value="record.name"
                           v-on:blur="updateRecord(record.id, 'name')"
                           v-on:keyup.enter="updateRecord(record.id, 'name')"
                           style="display: none">
                </div>
                <div>
                    <span class="text" v-on:dblclick="textToInput()">{{ record.score }}</span>
                    <select class="input"
                            v-on:blur="updateRecord(record.id, 'score')"
                            v-on:keyup.enter="updateRecord(record.id, 'score')"
                            style="display: none">
                        <option value="-20">very negative</option>
                        <option value="-10">negative</option>
                        <option value="0">neutral</option>
                        <option value="10">positive</option>
                        <option value="20">very positive</option>
                    </select>
                </div>
                <div>
                    <span class="text" v-on:dblclick="textToInput()">{{ record.duration }}</span>
                    <input class="input"
                           :value="record.duration"
                           v-on:blur="updateRecord(record.id, 'duration')"
                           v-on:keyup.enter="updateRecord(record.id, 'duration')"
                           style="display: none">
                </div>
                <button v-on:click="deleteRecord(record.id)">×</button>
            </li>
        </ul>
    </div>
</template>

<script>
    import Vue from 'vue';
    import api from '../store/api';

    export default {
        name: 'records_component',

        data() {
            return {
                records: [],
                recordToCreate: {},
            }
        },

        computed: {
            date() {
                return window.location.hash.split('=').pop();
            },

            defaultRecord() {
                return {
                    started_at: this.date,
                    score: 0
                }
            },
        },

        beforeMount() {
            this.getRecords();
            this.recordToCreate = this.defaultRecord;
        },

        methods: {
            getRecords() {
                api.getRecords(`&started_at=${this.date}`).then(response => {
                    this.records = response.data;
                });
            },

            createRecord() {
                api.createRecord(this.recordToCreate).then(response => {
                    this.getRecords();
                    this.recordToCreate = this.defaultRecord;
                })
            },

            updateRecord(id, type) {
                let value = this.inputToText();
                let recordToUpdate = {};
                recordToUpdate[type] = value;
                api.updateRecord(id, recordToUpdate).then(response => {
                    this.getRecords();
                })
            },

            textToInput() {
                let text = $(event.target);
                text.hide();
                let input = $('.input', text.parent());
                input.show();
                input.focus();
            },

            inputToText() {
                let input = $(event.target);
                input.hide();
                let text = $('.text', input.parent());
                text.show();

                return input.val();
            },

            deleteRecord(id) {
                api.deleteRecord(id).then(response => {
                    this.getRecords();
                })
            },

        }
    };
</script>

<style>
</style>