function get(path, param) {
    return axios.get(`/api/${path}?api_token=${apiToken}&${param}`)
        .then(response => {
            return response;
        })
        .catch(error => {
            console.log(error);
        });
}

function post(path, data, param) {
    return axios.post(`/api/${path}?api_token=${apiToken}&${param}`, data)
        .then(response => {
        return response;
        })
        .catch(error => {
            console.log(error);
        });
}

export default {

    getScores() {
        return get("scores/");
    },

    getRecords(date) {
        return get("records/", date);
    },

    createRecord(data) {
        return post("records/", data);
    }

}