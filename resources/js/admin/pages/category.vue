<template>
  <div>
    <div class="content">
      <div class="container-fluid">
        <div
          class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20"
        >
          <p class="_title0">
            Categories
            <Button @click="addModal = true">
              <Icon type="md-add" /> Add Category
            </Button>
          </p>

          <div class="_overflow _table_div">
            <table class="_table">
              <tr>
                <th>ID</th>
                <th>Tag name</th>
                <th>Created at</th>
                <th>Action</th>
              </tr>
              <tr v-for="(tag, i) in tags" :key="i" v-if="tags.length">
                <td>{{ tag.id }}</td>
                <td class="_table_name">{{ tag.tagName }}</td>
                <td>{{ tag.created_at }}</td>
                <td>
                  <Button
                    type="info"
                    size="small"
                    @click="showEditModal(tag, i)"
                    >Edit</Button
                  >
                  <Button
                    type="error"
                    size="small"
                    @click="showDeletingModal(tag, i)"
                    :loading="tag.isDeleting"
                  >
                    Delete
                  </Button>
                </td>
              </tr>
            </table>
          </div>
        </div>

        <!-- tag add model -->
        <Modal
          v-model="addModal"
          title="Add Category"
          :mask-closable="false"
          :closable="false"
        >
          <Input v-model="data.tagName" placeholder="Add category name..." />

          <div class="space"></div>

          <Upload
            ref="uploads"
            type="drag"
            :headers="{
              'x-csrf-token': token,
              'x-Requested-With': 'XMLHttpRequest',
            }"
            :format="['jpg', 'jpeg', 'png']"
            :on-success="handleSuccess"
            :on-error="handleError"
            :on-format-error="handleFormatError"
            :max-size="2048"
            :on-exceeded-size="handleMaxSize"
            action="/app/upload"
          >
            <div style="padding: 20px 0">
              <Icon
                type="ios-cloud-upload"
                size="52"
                style="color: #3399ff"
              ></Icon>
              <p>Click or drag files here to upload</p>
            </div>
          </Upload>

          <div class="demo-upload-list" v-if="data.iconImage">
            <img :src="`/uploads/${data.iconImage}`" />

            <div class="demo-upload-list-cover">
              <Icon type="ios-trash-outline" @click="deleteImage"></Icon>
            </div>
          </div>

          <div slot="footer">
            <Button type="default" @click="closeAddModal">Close</Button>
            <Button
              type="primary"
              @click="addTag"
              :disabled="isAdding"
              :loading="isAdding"
              >{{ isAdding ? "Adding.." : "Add Tag" }}</Button
            >
          </div>
        </Modal>

        <!-- tag edit model -->
        <Modal
          v-model="editModal"
          title="Edit Tag"
          :mask-closable="false"
          :closable="false"
        >
          <Input v-model="editData.tagName" placeholder="Edit tag name..." />

          <div slot="footer">
            <Button type="default" @click="closeEditModal">Close</Button>
            <Button
              type="primary"
              @click="editTag"
              :disabled="isAdding"
              :loading="isAdding"
              >{{ isAdding ? "Editing.." : "Edit Tag" }}</Button
            >
          </div>
        </Modal>

        <!-- deleting modal -->
        <Modal
          v-model="showDeleteModal"
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
              long
              :loading="isDeleting"
              :disabled="isDeleting"
              @click="deleteTag"
              >Delete</Button
            >
          </div>
        </Modal>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      data: {
        iconImage: "",
        categoryName: "",
      },
      addModal: false,
      isAdding: false,
      tags: [],
      editData: {
        iconImage: "",
        categoryName: "",
      },
      editModal: false,
      index: -1,
      showDeleteModal: false,
      isDeleting: false,
      deleteItem: {},
      deletingIndex: -1,
      token: "",
    };
  },
  methods: {
    closeAddModal() {
      this.addModal = false;
    },
    closeEditModal() {
      this.editModal = false;
    },
    async addTag() {
      if (this.data.tagName.trim() == "") return this.e("Tag name is required");
      const res = await this.callApi("post", "app/create_tag", this.data);
      if (res.status === 201) {
        this.tags.unshift(res.data);
        this.s("Tag has been added successfully!");
        this.addModal = false;
        this.data.tagName = "";
      } else {
        if (res.status === 422) {
          if (res.data.errors.tagName) {
            this.e(res.data.errors.tagName);
          }
        } else {
          this.swr();
        }
      }
    },
    showEditModal(tag, index) {
      let obj = {
        id: tag.id,
        tagName: tag.tagName,
      };

      this.editData = obj;
      this.editModal = true;
      this.index = index;
    },
    async editTag() {
      if (this.editData.tagName.trim() == "")
        return this.e("Tag name is required");
      const res = await this.callApi("post", "app/edit_tag", this.editData);
      if (res.status === 200) {
        this.tags[this.index].tagName = this.editData.tagName;
        this.s("Tag has been edited successfully!");
        this.editModal = false;
      } else {
        if (res.status === 422) {
          if (res.data.errors.tagName) {
            this.e(res.data.errors.tagName);
          }
        } else {
          this.swr();
        }
      }
    },
    showDeletingModal(tag, index) {
      this.deleteItem = tag;
      this.deletingIndex = index;
      this.showDeleteModal = true;
    },
    async deleteTag() {
      this.isDeleting = true;
      const res = await this.callApi("post", "app/delete_tag", this.deleteItem);
      if (res.status === 200) {
        this.tags.splice(this.deletingIndex, 1);
        this.s("Tag has been deleted successfuly");
      } else {
        this.swr();
      }
      this.isDeleting = false;
      this.showDeleteModal = false;
    },
    handleSuccess(res, file) {
      this.data.iconImage = res;
    },
    handleError(res, file) {
      this.$Notice.warning({
        title: "The file format is incorrect",
        desc: `${
          file.errors.file.length ? file.errors.file[0] : "Someting went wrong!"
        }`,
      });
    },
    handleFormatError(file) {
      this.$Notice.warning({
        title: "The file format is incorrect",
        desc:
          "File format of " +
          file.name +
          " is incorrect, please select jpg or png.",
      });
    },
    handleMaxSize(file) {
      this.$Notice.warning({
        title: "Exceeding file size limit",
        desc: "File  " + file.name + " is too large, no more than 2M.",
      });
    },
    async deleteImage() {
      let image = this.data.iconImage;
      this.data.iconImage = "";
      this.$refs.uploads.clearFiles();
      
      const res = await this.callApi("post", "app/delete_image", {
        imageName: image,
      });

      if (res.status != 200) {
        this.data.iconImage = image;
        this.swr();
      }
    },
  },
  async created() {
    this.token = window.laravel.csrfToken;
    const res = await this.callApi("get", "app/get_tags");
    if (res.status === 200) this.tags = res.data;
    else this.swr();
  },
};
</script>

<style scoped>
.demo-upload-list {
  display: inline-block;
  width: 60px;
  height: 60px;
  text-align: center;
  line-height: 60px;
  border: 1px solid transparent;
  border-radius: 4px;
  overflow: hidden;
  background: #fff;
  position: relative;
  box-shadow: 0 1px 1px rgba(0, 0, 0, 0.2);
  margin-right: 4px;
}
.demo-upload-list img {
  width: 100%;
  height: 100%;
}
.demo-upload-list-cover {
  display: none;
  position: absolute;
  top: 0;
  bottom: 0;
  left: 0;
  right: 0;
  background: rgba(0, 0, 0, 0.6);
}
.demo-upload-list:hover .demo-upload-list-cover {
  display: block;
}
.demo-upload-list-cover i {
  color: #fff;
  font-size: 20px;
  cursor: pointer;
  margin: 0 2px;
}
</style>