<template>
  <div>
    <Modal
      :value="getDeleteModalObj.showDeleteModal"
      width="360"
      :mask-closable="false"
      :closable="false"
    >
      <p slot="header" style="color: #f60; text-align: center">
        <Icon type="ios-information-circle"></Icon>
        <span>Delete confirmation</span>
      </p>
      <div style="text-align: center">
        <p>Are you sure you want to delete tag?</p>
      </div>
      <div slot="footer">
        <Button
          type="error"
          size="large"
          :loading="isDeleting"
          :disabled="isDeleting"
          @click="deleteCategory"
          >Delete</Button
        >
        <Button
          type="default"
          size="large"
          @click="closeModal"
          >Close</Button
        >
      </div>
    </Modal>
  </div>
</template>

<script>
import { mapGetters } from "vuex";

export default {
  data() {
    return {
      isDeleting: false,
    };
  },
  computed: {
    ...mapGetters(["getDeleteModalObj"]),
  },
  methods: {
    async deleteCategory() {
      this.isDeleting = true;
      const res = await this.callApi(
        "post",
        this.getDeleteModalObj.deleteUrl,
        this.getDeleteModalObj.data
      );
      if (res.status === 200) {
        this.s("Category has been deleted successfuly");
        this.$store.commit("setDeleteModal", true);
      } else {
        this.swr();
        this.$store.commit("setDeleteModal", false);
      }
      this.isDeleting = false;
    },
    closeModal() {
      this.$store.commit("setDeleteModal", false);
    }
  },
};
</script>