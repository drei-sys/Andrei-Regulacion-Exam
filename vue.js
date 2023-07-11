document.addEventListener('DOMContentLoaded', function () {
    new Vue({
        el: '#app',
        data: {
            datas: [],
            currentPage: 1,
            totalPages: 1,
        },
        mounted() {
            this.fetchData();
        },
        methods: {
            fetchData() {
                const channelsEndpoint = 'youtube_channel_json.php?page=' + this.currentPage;

                // Fetch channels data
                fetch(channelsEndpoint)
                    .then(response => response.json())
                    .then(data => {
                        this.channels = data.channel;
                        this.datas = data;
                        this.totalPages = data.totalPages;
                    })
                    .catch(error => console.error(error));
            },
            nextPage() {
                if (this.currentPage < this.totalPages) {
                    this.currentPage++;
                    this.fetchData();
                }
            },
            prevPage() {
                if (this.currentPage > 1) {
                    this.currentPage--;
                    this.fetchData();
                }
            },
        }
    });
});