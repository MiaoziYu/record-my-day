<template>
    <div>
        <h1>dashboard</h1>

        <div>
            <input type="text" placeholder="name" v-model="newRecord.name">
            <input type="date" v-model="newRecord.started_at">
            <select v-model="newRecord.score">
                <option value="-20">very negative</option>
                <option value="-10">negative</option>
                <option value="0">neutral</option>
                <option value="10">positive</option>
                <option value="20">very positive</option>
            </select>
            <input type="text" placeholder="duration" v-model="newRecord.duration">
            <button v-on:click="createRecord">submit</button>
        </div>

        <ul>
            <li v-for="score in scores"><a :href="getRecordUrl(score)">{{ score.score }}</a></li>
        </ul>
    </div>
</template>

<script>
import Vue from 'vue';
import api from '../store/api';

export default {
    name: 'dashboard_component',

    data() {
        return {
            scores: [],
            newRecord: {},
        }
    },

    computed: {
        defaultRecord() {
            return {
                score: 0
            }
        },
    },

    beforeMount() {
        this.getScores();
        this.newRecord = this.defaultRecord;
    },

    methods: {
        getScores() {
            api.getScores().then((response) => {
                this.scores = response.data[0];
            })
        },

        getRecordUrl(score) {
            return `/#/records/?date=${score.date}`;
        },

        createRecord() {
            api.createRecord(this.newRecord).then((response) => {
                this.newRecord = {};
                this.getScores();
            })
        },
    }
};
</script>

<style>
</style>