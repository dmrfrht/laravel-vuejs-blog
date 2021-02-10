<template>
  <div>
    <h1 ref="myref">Hook tutorial</h1>
    <p>My name is: {{ name }}</p>
  </div>
</template>

<script>
export default {
  data() {
    return {
      name: "ferhat",
    };
  },
  beforeCreate() {
    console.log(this.name);
  },
  created() {
    // call mostly initial data from it..
    console.log(
      "data and other function is avilable but not html: ",
      this.name
    );
  },
  // beforeMount() {
  //   console.log("ben before mounted hook u yum");
  // },
  mounted() {
    console.log(this.$refs.myref);
  },
};
</script>
