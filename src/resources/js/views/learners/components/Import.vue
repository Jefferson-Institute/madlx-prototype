<template>
  <div>
    <el-upload
      ref="upload"
      action="/api/learners/import"
      :auto-upload="false"
      :limit="1"
      :multiple="false"
      :headers="{ 'X-XSRF-TOKEN': xsrf_token }"
      accept=".csv"
      :on-success="handleOnSuccess"
      :on-error="handleOnError"
    >
      <el-button slot="trigger" type="primary">Select file</el-button>
      <span><i style="margin: 0px 10px;" class="el-icon-arrow-right" /></span>
      <el-button :loading="loading" type="success" @click="submitUpload">Upload to server</el-button>
    </el-upload>
    <div style="background: #fafafa;padding: 1px 15px;border-radius: 7px;margin-top: 30px;">
      <div style="font-size:14px;color: #606266;margin:15px 0">
        Data format accepted: CSV(.csv) file &#x3c; 100MB with 8 columns:<br><br>
        <strong>Date | Group | Objective | Grade | User | Email | Objective id | Group Grade | Country</strong><br>
        -a column that contains the date of the observation in the format: year-month-day<br>
        -a column that contains the group should include group name<br>
        -a column that contains the user should include user id and username<br>
        -a column that contains the user should include user id and Email<br>
        -a column that contains Objective id should include objective id<br>
        -a column that contains Grade should include user's grade<br>
        -a column that contains Country should include country name
      </div>
      <div style="font-size:14px;color: #606266;margin-bottom:15px" />
    </div>
  </div>
</template>

<script>
import Cookies from 'js-cookie';
const example = require('@/assets/learners-example.jpg').default;
export default {
  name: 'LearnersImport',
  props: [

  ],
  data() {
    return {
      example: example,
      xsrf_token: undefined,
      loading: false,
    };
  },
  created() {
    this.xsrf_token = Cookies.get('XSRF-TOKEN');
  },
  methods: {
    submitUpload() {
      this.loading = true;
      this.$refs.upload.submit();
    },
    handleOnSuccess() {
      this.$refs.upload.clearFiles();
      this.loading = false;
      this.$notify({
        title: 'Success',
        message: 'Imported Learners successfully',
        type: 'success',
        duration: 2000,
      });
      this.$emit('learnersImported');
    },
    handleOnError() {
      this.loading = false;
      this.$notify({
        title: 'Error',
        message: 'Learners NOT imported.',
        type: 'error',
        duration: 2000,
      });
    },
  },

};
</script>

<style lang="scss" scoped>

</style>
