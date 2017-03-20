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
            <button @click="createRecord">submit</button>
        </div>

        <ul class="record-list">
            <li class="record" v-for="record in records">
                <div>
                    <span class="text__edit" @dblclick="inputManager.textToInput">{{ record.name }}</span>
                    <input class="input__edit"
                           :value="record.name"
                           @blur="updateRecord(record.id, 'name')"
                           @keyup.enter="updateRecord(record.id, 'name')"
                           style="display: none">
                </div>
                <div>
                    <span class="text__edit" @dblclick="inputManager.textToInput">{{ record.score }}</span>
                    <select class="input__edit"
                            @blur="updateRecord(record.id, 'score')"
                            @keyup.enter="updateRecord(record.id, 'score')"
                            style="display: none">
                        <option value="-20">very negative</option>
                        <option value="-10">negative</option>
                        <option value="0">neutral</option>
                        <option value="10">positive</option>
                        <option value="20">very positive</option>
                    </select>
                </div>
                <div>
                    <span class="text__edit" @dblclick="inputManager.textToInput">{{ record.duration }}</span>
                    <input class="input__edit"
                           :value="record.duration"
                           @blur="updateRecord(record.id, 'duration')"
                           @keyup.enter="updateRecord(record.id, 'duration')"
                           style="display: none">
                </div>
                <button @click="deleteRecord(record.id)">×</button>
            </li>
        </ul>
    </div>
</template>

<script>

    import Vue from "vue";
    import api from "../store/api";
    import { InputManager } from "../classes/";

    export default {
        name: "records_component",

        data() {
            return {
                records: [],
                recordToCreate: {
                    started_at: this.date,
                    score: 0,
                },
                inputManager: new InputManager(),
            }
        },

        computed: {
            date() {
                return window.location.hash.split('=').pop();
            },
        },

        beforeMount() {
            this.getRecords();
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
                    this.recordToCreate = {
                        started_at: this.date,
                        score: 0                    };
                })
            },

            updateRecord(id, type) {
                let value = this.inputManager.inputToText();
                let recordToUpdate = {};
                recordToUpdate[type] = value;
                api.updateRecord(id, recordToUpdate).then(response => {
                    this.getRecords();
                })
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