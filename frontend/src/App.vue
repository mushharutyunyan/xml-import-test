<template>
  <ProductsView msg="Welcome to Imported products view"/>
</template>

<script>
import ProductsView from './components/ProductsView.vue'

export default {
  name: 'App',
  components: {
    ProductsView
  },
  methods: {

  },
  mounted() {
  }
}
</script>

<style>
#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
  margin-top: 60px;
}
.main {
  padding: 20px;
}

table {
  width: 100%;
  border-collapse: collapse;
}

.table-container {
  width: 100%;
}
.gray-table-row {
  background-color: #ebebeb;
}
table > thead {
  background: green;
  color: white;
}
table > thead > tr > th {
  cursor: pointer;
}

caption,
th,
td {
  padding: 1rem;
  line-height: 1.5;
}
tr:nth-of-type(2n) {
  background: hsl(0% 0% 05 / 0.1);
}

@media only screen and (max-width: 650px) {
  th {
    display: none;
  }
  td {
    display: grid;
    grid-template-columns: 15ch auto;
  }

  td::before {
    content: attr(data-cell) ": ";
    font-weight: 700;
    text-transform: capitalize;
  }
}

</style>
