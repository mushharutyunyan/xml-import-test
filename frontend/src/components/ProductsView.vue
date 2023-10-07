<template>
  <div class="main">
    <div v-if="loading" class="loader">
      <img src="../assets/loading.gif" width="100" height="100">
    </div>
    <div class="row">
      <span v-if="errorMessage" style="color: red">{{ errorMessage }}</span>
    </div>
    <div>
      <p>Scroll down for pagination</p>
    </div>
    <div>
      <p>Search - Minimum 2 symbols</p>
      <input type="text" v-model="search" placeholder="Search by name, code, usage">
    </div>
    <div v-if="products.data.length">
      <div class="table-wrapper paginated-list" style="margin-top: 15px"
           @scroll="onScrollCallback($event, 'products','productList')">
        <div class="table-container">
          <table>
            <thead>
            <tr>
              <th v-for="(column, index) in Object.keys(products.data[0])" :key="index">{{ column }}</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(product, index) in products.data" :key="index">
              <td v-for="(rowValue, rowValueIndex) in product" :key="rowValueIndex">{{ rowValue }}</td>
            </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>
<style scoped>
::-webkit-input-placeholder {
  color: #ccc;
  text-align: left;
  text-indent: 1rem;
  font-size: .9rem;
  letter-spacing: .05rem;
}

:-moz-placeholder {
  color: #ccc;
  text-align: left;
  text-indent: 1rem;
  font-size: .9rem;
  letter-spacing: .05rem;
}

::-moz-placeholder {
  color: #ccc;
  text-align: left;
  text-indent: 1rem;
  font-size: .9rem;
  letter-spacing: .05rem;
}

:-ms-input-placeholder {
  color: #ccc;
  text-align: left;
  text-indent: 1rem;
  font-size: .9rem;
  letter-spacing: .05rem;
}

input, button {
  height: 3rem;
  box-sizing: border-box;
  width: 100%;
}

input {
  border: 1px solid #ddd;;
  background: #fafafa;
  text-indent: 1rem;
  font-size: 1rem;
}

.btn {
  color: #FFFFFF;
  background-color: #2196F3;
  border: 1px solid #127ACD;
  padding: 0 1rem;
  font-size: 1rem;
  font-weight: bold;
  cursor: pointer;
}

.btn:hover {
  background-color: #127ACD;
}

.wrapp {
  display: flex;
  flex-wrap: wrap;
  margin-bottom: 0;
  padding: 1rem;
  width: 80%;
  min-width: 480px;
  margin: 0 auto;
}

.item-1 {
  flex: 1;
}

.item-2, .item-3 {
  min-width: 50px;
}

.loader {
  position: fixed;
  left: 0px;
  top: 0px;
  width: 100%;
  height: 100%;
  z-index: 9999;
}

.paginated-list {
  max-height: 500px;
  overflow-y: auto;
  overflow-x: auto;
}
</style>
<script>
import axios from "axios";

export default {
  name: 'ParserView',
  components: {},
  data() {
    return {
      search: null,
      products: {
        data: [],
        prependedData: [],
        settings: {
          scrollProcessingTime: null,
          scrollHeight: null,
          scrollTop: null,
          clientHeight: null,
          offset: null,
          limit: 20,
        }
      },
      errorMessage: null,
      loading: false
    }
  },
  watch: {
    search() {
      let self = this;
      if(!self.search.length || self.search.length > 1) {
        self.resetPaginationSettings('products').then(function() {
          self.productList();
        })
      }
    }
  },
  methods: {
    async productList() {
      let self = this;
      self.errorMessage = null;
      self.loading = true;
      await axios.post("http://172.20.0.10/home/products", {
        limit: self.products.settings.limit,
        offset: self.products.settings.offset,
        search: self.search
      }).then(function (response) {
        self.loading = false;
        if (self.products.settings.offset > 0) {
          self.products.prependedData = response.data.data
          self.products.data = self.products.data.concat(response.data.data);
        } else {
          self.products.data = response.data.data
        }
      }).catch((error) => {
        self.errorMessage = error.response.data.message;
        self.loading = false;
      });
    },

    async onScrollCallback(event, listName, listMethodName) {
      const self = this;
      if (!self.scrollProcessingTime) {
        if (event.target.scrollLeft === 0) {
          self[listName].settings.scrollHeight = event.target.scrollHeight
          self[listName].settings.scrollTop = event.target.scrollTop
          self[listName].settings.clientHeight = event.target.clientHeight
          if (self[listName].settings.scrollTop + self[listName].settings.clientHeight + 50 >= self[listName].settings.scrollHeight) {
            if (!self[listName].settings.offset) {
              if (self[listName].settings.limit === self[listName].data.length) {
                self[listName].settings.offset += self[listName].settings.limit;
              }
            } else {
              if (self[listName].data.length >= self[listName].settings.limit) {
                self.scrollProcessingTime = Date.now();
                self[listMethodName]().then(function () {
                  if (self[listName].prependedData.length === self[listName].settings.limit) {
                    self.scrollProcessingTime = null;
                    self[listName].settings.offset += self[listName].settings.limit;
                  }
                });
              }
            }
          }
        }
      }
    },
    async resetPaginationSettings(listName) {
      this[listName].settings.offset = null;
      this[listName].prependedData = [];
      this.scrollProcessingTime = null;
    },
  },
  mounted() {
    this.productList();
  }
}
</script>

