<template>
  <div style="margin-left:-20px;margin-right:-20px;margin-top:-17px;">
    <v-table :data="body" :current-page.sync="currentPage" :page-size="pageSize" @totalPagesChanged="totalPages = $event">
      <thead slot="head">
        <th v-for="(test, index) in headers" :key="index" style="border: 1px solid #e6ebf5;border-bottom:2px solid #e6ebf5;text-transform: uppercase;">{{ test.text }}</th>
      </thead>
      <tbody slot="body" slot-scope="{displayData}">
        <tr v-for="(row, index) in displayData" :key="index">
          <td v-for="(h, index2) in headers" :key="index2" style="border: 1px solid #e6ebf5;">{{ row[h.value] }}</td>
        </tr>
      </tbody>
    </v-table>
    <smart-pagination
      :hide-single-page="false"
      :current-page.sync="currentPage"
      :total-pages="totalPages"
      :max-page-links="4"
      :boundary-links="true"
    />
  </div>
</template>

<script>
export default {
  name: 'TableChart',
  props: {
    pageSize: {
      type: Number,
      required: false,
      default: 8,
    },
    resultSet: {
      type: Object,
      required: true,
    },
    pivotConfig: {
      type: Object,
      required: true,
      default() {
        return {};
      },
    },
  },
  data: () => ({
    body: [],
    headers: [],
    currentPage: 1,
    totalPages: 0,
  }),
  mounted() {
    this.getHeaders();
    this.getBody();
  },
  methods: {
    getHeaders() {
      const data = this.resultSet.tablePivot(this.pivotConfig);
      const hd = Object.keys(data[0]).map((key) => {
        return {
          text: key.split('.')[1].split(/(?=[A-Z])/).join(' '),
          value: key,
        };
      });
      this.headers = hd;
    },
    getBody() {
      const data = this.resultSet.tablePivot(this.pivotConfig);
      const desserts = data.map((key) => {
        return key;
      });
      this.body = desserts;
    },
  },
};
</script>
<style>
  table {
    border-collapse: collapse;
    width: 100%;
    font-size: 13px;
    color: #757575;
    white-space: nowrap;
  }

  table tr td, table th {
    padding: 12px 22px;
    border: none !important;
    border-bottom: 1px solid #e6ebf5 !important;
  }

  table tr:nth-child(even){background-color: #F0F0F0;}

  table tr:hover {background-color: #eae9e7;}

  table th {
    padding-top: 12px;
    padding-bottom: 12px;
    text-align: left;
    text-transform: capitalize!important;
    font-size: 13px;
  }
  .pagination {
    display: -ms-flexbox;
    display: flex;
    padding-left: 0;
    list-style: none;
    border-radius: 0.25rem;
  }

  .page-link {
    position: relative;
    display: block;
    padding: 0.5rem 0.75rem;
    color: #2c587b;
    background-color: #fff;
  }

  .page-link:not(:disabled):not(.disabled) {
    cursor: pointer;
  }
</style>
