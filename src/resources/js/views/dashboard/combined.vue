<template>
  <div style="padding:32px">
    <el-row :gutter="12" style="margin-bottom: 12px;">
      <el-col :xs="24" :sm="8" :lg="8">
        <query-renderer :cubejs-api="cubejsApi" :query="{ 'measures': ['Statements.count'], 'limit': 1000, 'order': [] }" style="height:100%">
          <template v-slot="{ resultSet }">
            <div v-loading="!resultSet" style="height:160px">
              <div v-if="resultSet" style="height:100%">
                <counter :pivot-config="{}" title="Total Statements" :result-set="resultSet" icon="el-icon-s-data" color="#7AA535" />
              </div>
            </div>
          </template>
        </query-renderer>
      </el-col>
      <el-col :xs="24" :sm="8" :lg="8">
        <query-renderer :cubejs-api="cubejsApi" :query="{ 'measures': ['Statements.Sources'], 'limit': 1000, 'order': [] }" style="height:100%">
          <template v-slot="{ resultSet }">
            <div v-loading="!resultSet" style="height:160px">
              <div v-if="resultSet" style="height:100%">
                <counter :pivot-config="{}" title="Sources" :result-set="resultSet" color="#35A07C" />
              </div>
            </div>
          </template>
        </query-renderer>
      </el-col>
      <el-col :xs="24" :sm="8" :lg="8">
        <query-renderer :cubejs-api="cubejsApi" :query="{ 'measures': ['Statements.Users'], 'limit': 1000, 'order': [] }" style="height:100%">
          <template v-slot="{ resultSet }">
            <div v-loading="!resultSet" style="height:160px">
              <div v-if="resultSet" style="height:100%">
                <counter :pivot-config="{}" title="Users" icon="el-icon-user-solid" :result-set="resultSet" color="#339EA8" />
              </div>
            </div>
          </template>
        </query-renderer>
      </el-col>
    </el-row>
    <el-row :gutter="12" style="margin-bottom: 12px;">
      <el-col :xs="24" :sm="12" :lg="12">
        <query-renderer :cubejs-api="cubejsApi" :query="{'measures':['Statements.ScoreAll'],'dimensions':['Statements.actorName','Statements.objectDefinitionNameUnd'],'filters':[{'member':'Statements.resultScoreScaled','operator':'gt','values':['0']}],'limit':10000,'order':[]}" style="height:100%">
          <template v-slot="{ resultSet }">
            <el-card v-loading="!resultSet" element-loading-background="transparent" class="box-card" style="height:100%" shadow="never">
              <div slot="header" class="clearfix">
                <span style="text-transform: uppercase; line-height: 1.4;">Objectives Correlation Matrix</span>
              </div>
              <div v-if="resultSet" style="height:100%;overflow-x: hidden;">
                <ChartRenderer chart-type="correlogram" :pivot-config="{}" :result-set="resultSet" file-name="Objectives Correlation Matrix" />
              </div>
            </el-card>
          </template>
        </query-renderer>
      </el-col>
      <el-col :xs="24" :sm="12" :lg="12">
        <query-renderer :cubejs-api="cubejsApi" :query="{'measures':['Statements.ScoreAll'],'dimensions':['Statements.actorName','Statements.objectDefinitionNameUnd'],'filters':[{'member':'Statements.resultScoreScaled','operator':'set','values':[null]}, {'member':'Statements.objectDefinitionNameUnd','operator':'set','values':[null]}],'limit':10000,'order':[]}" style="height:100%">
          <template v-slot="{ resultSet }">
            <el-card v-loading="!resultSet" element-loading-background="transparent" class="box-card" style="height:100%" shadow="never">
              <div slot="header" class="clearfix">
                <span style="text-transform: uppercase; line-height: 1.4;">Objectives Correlation Input</span>
              </div>
              <div v-if="resultSet" style="max-height:600px">
                <ChartRenderer chart-type="table" :pivot-config="{}" :result-set="resultSet" :page-size="13" file-name="Objectives Correlation Input" />
              </div>
            </el-card>
          </template>
        </query-renderer>
      </el-col>
    </el-row>
    <el-row :gutter="12" style="margin-bottom: 12px;">
      <el-col :xs="24" :sm="14" :lg="14">
        <query-renderer :cubejs-api="cubejsApi" :query="{'measures':['Statements.count'],'dimensions':['Statements.authorityName'],'timeDimensions':[{'dimension':'Statements.timestamp','granularity':'day'}],'limit':1000,'order':[]}" style="height:100%">
          <template v-slot="{ resultSet }">
            <el-card v-loading="!resultSet" element-loading-background="transparent" class="box-card" style="height:100%" shadow="never">
              <div slot="header" class="clearfix">
                <span style="text-transform: uppercase; line-height: 1.4;">Number of statements per Source timeline</span>
              </div>
              <div v-if="resultSet" style="height:100%">
                <ChartRenderer chart-type="line" :pivot-config="{'x':['Statements.timestamp.day'],'y':['Statements.authorityName','measures']}" :result-set="resultSet" :curve="true" file-name="Number of statements per Source timeline" />
              </div>
            </el-card>
          </template>
        </query-renderer>
      </el-col>
      <el-col :xs="24" :sm="10" :lg="10">
        <query-renderer :cubejs-api="cubejsApi" :query="{&quot;measures&quot;:[&quot;Statements.coursesAVGScore&quot;],&quot;dimensions&quot;:[&quot;Statements.actorName&quot;,&quot;Statements.authorityName&quot;],&quot;filters&quot;:[{&quot;member&quot;:&quot;Statements.coursesAVGScore&quot;,&quot;operator&quot;:&quot;gt&quot;,&quot;values&quot;:[&quot;0&quot;]}],&quot;limit&quot;:1000,&quot;order&quot;:[[&quot;Statements.actorName&quot;,&quot;asc&quot;]]}" style="height:100%">
          <template v-slot="{ resultSet }">
            <el-card v-loading="!resultSet" element-loading-background="transparent" class="box-card" style="height:100%" shadow="never">
              <div slot="header" class="clearfix">
                <span style="text-transform: uppercase; line-height: 1.4;">Learner Average scores per source</span>
              </div>
              <div v-if="resultSet" style="height:100%">
                <ChartRenderer chart-type="scatter" :pivot-config="{&quot;x&quot;:[&quot;Statements.actorName&quot;],&quot;y&quot;:[&quot;Statements.authorityName&quot;,&quot;measures&quot;]}" :result-set="resultSet" ytitle="Average Score" url="learners" file-name="Learner Average scores per source" />
              </div>
            </el-card>
          </template>
        </query-renderer>
      </el-col>
    </el-row>
    <el-row :gutter="12" style="margin-bottom: 12px;">
      <el-col :xs="24" :sm="8" :lg="8">
        <query-renderer :cubejs-api="cubejsApi" :query="{&quot;measures&quot;:[&quot;Statements.coursesAVGScore&quot;],&quot;dimensions&quot;:[&quot;Statements.authorityName&quot;],&quot;limit&quot;:1000,&quot;order&quot;:[]}" style="height:100%">
          <template v-slot="{ resultSet }">
            <el-card v-loading="!resultSet" element-loading-background="transparent" class="box-card" style="height:100%" shadow="never">
              <div slot="header" class="clearfix">
                <span style="text-transform: uppercase; line-height: 1.4;">Average Scores per Source</span>
              </div>
              <div v-if="resultSet" style="height:100%">
                <ChartRenderer chart-type="column" :pivot-config="{}" :result-set="resultSet" xtitle="Source Name" ytitle="Average Score" color="#339EA8" file-name="Average Scores per Source" />
              </div>
            </el-card>
          </template>
        </query-renderer>
      </el-col>
      <el-col :xs="24" :sm="16" :lg="16">
        <query-renderer :cubejs-api="cubejsApi" :query="{'measures':['Statements.count'],'dimensions':['Statements.objectDefinitionNameUnd'],'filters':[{'member':'Statements.resultScoreScaled','operator':'set','values':['']}],'limit':10,'order':[]}" style="height:100%">
          <template v-slot="{ resultSet }">
            <el-card v-loading="!resultSet" element-loading-background="transparent" class="box-card" style="height:100%" shadow="never">
              <div slot="header" class="clearfix">
                <span style="text-transform: uppercase; line-height: 1.4;">Top 10 Objectives</span>
              </div>
              <div v-if="resultSet" style="height:100%">
                <ChartRenderer chart-type="column" :legend="true" :pivot-config="{}" :result-set="resultSet" ytitle="Statements Count" xtitle="Course/Objective" color="#7AA535" :inside="true" file-name="Top 10 Objectives" />
              </div>
            </el-card>
          </template>
        </query-renderer>
      </el-col>
    </el-row>
  </div>
</template>

<script>
import Counter from '@/components/Charts/Counter.vue';
import cubejs from '@cubejs-client/core';
import ChartRenderer from '@/components/Charts/ChartRenderer';
import { QueryRenderer } from '@cubejs-client/vue';
const API_URL = process.env.MIX_API_URL + ':4000';
const CUBEJS_TOKEN =
  'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJPbmxpbmUgSldUIEJ1aWxkZXIiLCJpYXQiOjE2MTMwNDg1MjMsImV4cCI6MTY0NDU4NDUyNCwiYXVkIjoiZGFzaC5qZWZmZXJzb25ob3N0aW5nLm9yZyIsInN1YiI6IiIsIkVtYWlsIjoiYWRtaW5AamVmZmVyc29uaG9zdGluZy5vcmcifQ.mSfNczOIZyfXXCrU61WCwWVB1ojQCy3WjUy_8WAGnz8';
const cubejsApi = cubejs(CUBEJS_TOKEN, {
  apiUrl: `${API_URL}/cubejs-api/v1`,
});
const cubejsApi2 = cubejs(CUBEJS_TOKEN, {
  apiUrl: `${API_URL}/cubejs-api/v1`,
});

export default {
  name: 'CombinedDashboard',
  components: {
    QueryRenderer,
    ChartRenderer,
    Counter,
  },
  props: {
    authorities: {
      type: Array,
      required: true,
    },
  },
  data() {
    return {
      learners: false,
      countries: [],
      objectivesAvg: [],
      objectivesLearnersCount: undefined,
      learnersCountryCount: undefined,
      topCombinedUsers: false,
      cubejsApi,
      cubejsApi2,
    };
  },
  created() {
    this.getTopCombinedUsers();
  },
  methods: {
    getTopCombinedUsers() {
      let data = [];
      const promises = [];
      this.authorities.forEach(authority => {
        const promise = this.cubejsApi.load({
          'measures': ['Statements.coursesAVGScore'],
          'dimensions': ['Statements.actorName'],
          'filters': [
            { 'member': 'Statements.authorityName', 'operator': 'equals', 'values': [authority] },
            { 'member': 'Statements.coursesAVGScore', 'operator': 'gt', 'values': ['0'] },
          ],
          'limit': 1000,
          'order': [['Statements.coursesAVGScore', 'desc']],
        }).then((resultSet) => {
          data = data.concat(resultSet.series().map((seriesItem) => ({
            name: authority,
            data: seriesItem.series.map((item) => [item.x, item.value]),
          })));
        });
        promises.push(promise);
      });

      Promise.all(promises).then(() => {
        const allUsers = {};
        const repeated = [];
        data.forEach((authority) => {
          authority.data.forEach((user) => {
            if (user[0] in allUsers) {
              repeated.push({ user: user[0], score: allUsers[user[0]] + user[1] });
            }
            allUsers[user[0]] = user[1];
          });
        });

        repeated.sort((a, b) => (a.score > b.score) ? 1 : ((b.score > a.score) ? -1 : 0));
        const topUSers = repeated.reverse().slice(0, 10).map(i => i.user);

        const final = {};
        data.forEach((authority) => {
          final[authority.name] = [];
        });
        topUSers.forEach((topUser) => {
          data.forEach((authority) => {
            authority.data.forEach((user) => {
              if (topUser === user[0]) {
                final[authority.name].push(user);
              }
            });
          });
        });
        const topCombinedUsers = [];

        for (var key in final) {
          if (final.hasOwnProperty(key)) {
            topCombinedUsers.push({ name: key, data: final[key] });
          }
        }
        this.topCombinedUsers = topCombinedUsers;
      });
    },
    formatProgress(percentage) {
      return percentage.toFixed(2);
    },
    stringToColour(str) {
      var hash = 0;
      for (var i = 0; i < str.length; i++) {
        hash = str.charCodeAt(i) + ((hash << 5) - hash);
      }
      var colour = '#';
      for (i = 0; i < 3; i++) {
        var value = (hash >> (i * 8)) & 0xFF;
        colour += ('00' + value.toString(16)).substr(-2);
      }
      return colour;
    },
  },
};
</script>
