<template>
    <div>
        <h1>dashboard</h1>

        <div>
            <input type="text" name='name' placeholder="name" v-model="newRecord.name">
            <input type="date" name='started_at' placeholder="started_at" v-model="newRecord.started_at">
            <input type="text" name='score' placeholder="score" v-model="newRecord.score">
            <input type="text" name='duration' placeholder="duration" v-model="newRecord.duration">
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

    beforeMount() {
        this.getScores()
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
        }
    }
};
</script>

<style>
</style>