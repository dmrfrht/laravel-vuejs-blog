<template>
  <div>
    <div class="content">
      <div class="container-fluid">
        <h1>i will show how all other components react to changes</h1>
        <h2>THE MASTER COMPONENT : {{ $this.state.counter }}</h2>

        <br />
        <hr />
        <br />

        <component-a></component-a>
        <hr />
        <component-b></component-b>
        <hr />
        <component-c></component-c>

        <div>
          <Button type="info" @click="changeCounter"
            >Change the state of the counter</Button
          >
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import comA from "./comA";
import comB from "./comB";
import comC from "./comC";
import { mapGetters, mapActions } from "vuex";

export default {
  components: {
    "component-a": comA,
    "component-b": comB,
    "component-c": comC,
  },
  computed: {
    ...mapGetters({
      counter: "getCounter",
    }),
  },
  data() {
    return {};
  },
  methods: {
    ...mapActions(["changeCounterAction"]),
    changeCounter() {
      this.$store.dispatch("changeCounterAction", 1);
      // this.$store.commit("changeTheCounter", 1);
    },
  },
};
</script>