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
                <th>Icon image</th>
                <th>Category name</th>
                <th>Created at</th>
                <th>Action</th>
              </tr>
              <tr
                v-for="(category, i) in categories"
                :key="i"
                v-if="categories.length"
              >
                <td>{{ category.id }}</td>
                <td class="table_image">
                  <img :src="category.iconImage" alt="" />
                </td>
                <td class="_table_name">{{ category.categoryName }}</td>
                <td>{{ category.created_at }}</td>
                <td>
                  <Button
                    type="info"
                    size="small"
                    @click="showEditModal(category, i)"
                    >Edit</Button
                  >
                  <Button
                    type="error"
                    size="small"
                    @click="showDeletingModal(category, i)"
                    :loading="category.isDeleting"
                  >
                    Delete
                  </Button>
                </td>
              </tr>
            </table>
          </div>
        </div>

        <!-- category add model -->
        <Modal
          v-model="addModal"
          title="Add Category"
          :mask-closable="false"
          :closable="false"
        >
          <Input
            v-model="data.categoryName"
            placeholder="Add category name..."
          />

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
            <img :src="`${data.iconImage}`" />

            <div class="demo-upload-list-cover">
              <Icon type="ios-trash-outline" @click="deleteImage"></Icon>
            </div>
          </div>

          <div slot="footer">
            <Button type="default" @click="closeAddModal">Close</Button>
            <Button
              type="primary"
              @click="addCategory"
              :disabled="isAdding"
              :loading="isAdding"
              >{{ isAdding ? "Adding.." : "Add Category" }}</Button
            >
          </div>
        </Modal>

        <!-- category edit model -->
        <Modal
          v-model="editModal"
          title="Edit Category"
          :mask-closable="false"
          :closable="false"
        >
          <Input
            v-model="editData.categoryName"
            placeholder="Edit category name..."
          />

          <div class="space"></div>

          <Upload
            v-show="isIconNew"
            ref="editDataUploads"
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

          <div class="demo-upload-list" v-if="editData.iconImage">
            <img :src="`${editData.iconImage}`" />

            <div class="demo-upload-list-cover">
              <Icon type="ios-trash-outline" @click="deleteImage(false)"></Icon>
            </div>
          </div>

          <div slot="footer">
            <Button type="default" @click="closeEditModal">Close</Button>
            <Button
              type="primary"
              @click="editCategory"
              :disabled="isAdding"
              :loading="isAdding"
              >{{ isAdding ? "Editing.." : "Edit Category" }}</Button
            >
          </div>
        </Modal>

        <!-- deleting modal -->
        <!-- <Modal
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
        </Modal> -->

        <deleteModal></deleteModal>
      </div>
    </div>
  </div>
</template>

<script>
import deleteModal from "../../admin/components/deleteModal";

export default {
  data() {
    return {
      data: {
        iconImage: "",
        categoryName: "",
      },
      addModal: false,
      isAdding: false,
      categories: [],
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
      isIconNew: false,
      isEditingItem: false,
    };
  },
  methods: {
    closeAddModal() {
      this.addModal = false;
    },
    async addCategory() {
      if (this.data.categoryName.trim() == "")
        return this.e("Category name is required");
      if (this.data.iconImage.trim() == "")
        return this.e("Icon image is required");
      this.data.iconImage = `/uploads/${this.data.iconImage}`;
      const res = await this.callApi("post", "app/create_category", this.data);
      if (res.status === 201) {
        this.categories.unshift(res.data);
        this.s("Category has been added successfully!");
        this.addModal = false;
        this.data.categoryName = "";
        this.data.iconImage = "";
      } else {
        if (res.status === 422) {
          if (res.data.errors.categoryName) {
            this.e(res.data.errors.categoryName[0]);
          }

          if (res.data.errors.iconImage) {
            this.e(res.data.errors.iconImage[0]);
          }
        } else {
          this.swr();
        }
      }
    },
    showEditModal(category, index) {
      // let obj = {
      //   id: tag.id,
      //   tagName: tag.tagName,
      // };

      this.editData = category;
      this.editModal = true;
      this.index = index;
      this.isEditingItem = true;
    },
    async editCategory() {
      if (this.editData.categoryName.trim() == "")
        return this.e("Category name is required");
      if (this.editData.iconImage.trim() == "")
        return this.e("Icon image is required");
      const res = await this.callApi(
        "post",
        "app/edit_category",
        this.editData
      );
      if (res.status === 200) {
        this.categories[this.index].categoryName = this.editData.categoryName;
        // this.categories[this.index].iconImage = this.editData.iconImage;
        this.s("Category has been edited successfully!");
        this.editModal = false;
      } else {
        if (res.status === 422) {
          if (res.data.errors.categoryName) {
            this.e(res.data.errors.categoryName);
          }

          if (res.data.errors.iconImage) {
            this.e(res.data.errors.iconImage);
          }
        } else {
          this.swr();
        }
      }
    },
    showDeletingModal(tag, index) {
      const deleteModalObj = {
        showDeleteModal: true,
        deleteUrl: "app/delete_tag",
        data: tag,
        deletingIndex: index,
        isDeleted: false,
      };

      // this.deleteItem = tag;
      // this.deletingIndex = index;
      // this.showDeleteModal = true;
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
      res = `/uploads/${res}`;
      if (this.isEditingItem) {
        return (this.editData.iconImage = res);
      }
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
    async deleteImage(isAdd = true) {
      let image;
      if (!isAdd) {
        // for editing
        this.isIconNew = true;
        image = this.editData.iconImage;
        this.editData.iconImage = "";
        this.$refs.editDataUploads.clearFiles();
      } else {
        image = this.data.iconImage;
        this.data.iconImage = "";
        this.$refs.uploads.clearFiles();
      }

      const res = await this.callApi("post", "app/delete_image", {
        imageName: image,
      });

      if (res.status != 200) {
        this.data.iconImage = image;
        this.swr();
      }
    },
    closeEditModal() {
      this.isEditingItem = false;
      this.editModal = false;
    },
  },
  async created() {
    this.token = window.laravel.csrfToken;
    const res = await this.callApi("get", "app/get_category");
    if (res.status === 200) this.categories = res.data;
    else this.swr();
  },
  components: {
    deleteModal,
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