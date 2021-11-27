<template>
  <div style="padding: 0 24px 24px;">
    <h1 style="text-align: center;color: #304156;padding-top: 35px;text-transform: uppercase;">All Learners</h1>
    <el-row :gutter="20">
      <el-col :xs="24">
        <el-card shadow="never" class="box-card" style="margin:32px 0">
          <el-button-group style="display:flex;margin-bottom:0px">
            <el-input v-model="searchValue" class="input-with-select">
              <template slot="prepend">Search</template>
              <el-button slot="append" icon="el-icon-search" />
            </el-input>
          </el-button-group>
        </el-card>
      </el-col>
    </el-row>

    <el-row v-loading="loading" :gutter="20" style="min-height:300px" element-loading-background="rgba(255, 255, 255, 0)">
      <el-col v-for="learner in filteredLearners" :key="learner.email" :md="6" :sm="12">
        <el-card shadow="never" class="box-card" style="height:100%;margin-bottom:20px;cursor:pointer;" @click.native="showLearner(learner.email, learner.username)">
          <div slot="header" class="clearfix" style="display: flex;justify-content: center;align-items: center;">
            <el-avatar :size="120" icon="el-icon-user-solid" style="font-size: 62px;background: #eae9e7;" />
          </div>
          <div style="text-align: center;">{{ learner.username }}</div>
          <div style="text-align: center;font-size: 11px;font-style: normal;opacity: 0.6;">{{ learner.email }}</div>
        </el-card>
      </el-col>
    </el-row>
  </div>
</template>
<script>
import axios from 'axios';

export default {
  name: 'Learners',
  data() {
    return {
      searchValue: '',
      learners: [],
      filteredLearners: [],
      loading: true,
    };
  },
  watch: {
    searchValue(val) {
      if (this.searchValue !== '' && val) {
        this.filteredLearners = this.learners.filter((item) => {
          return item.username
            .toUpperCase()
            .includes(val.toUpperCase());
        });
      } else {
        this.filteredLearners = this.learners;
      }
    },
  },
  created() {
    this.getLearners();
  },
  methods: {
    showLearner(email, username) {
      this.$router.push({ name: 'learner', params: { email: email, username: username }});
    },
    async getLearners() {
      this.loading = true;
      axios.get('/api/learners/lrs_users').then(response => {
        this.learners = response.data.data;
        this.filteredLearners = this.learners;
      }).then(() => {
        this.loading = false;
      });
    },
  },
};
</script>

<style rel="stylesheet/scss" lang="scss" scoped>

</style>
