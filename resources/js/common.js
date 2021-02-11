import Axios from "axios"

export default {
  data() {
    return {}
  },
  methods: {
    async callApi(method, url, dataObj) {
      try {
        return await Axios({
          method,
          url,
          data: dataObj
        })
      } catch (e) {
        return e.response
      }
    },
    i(desc, title = "Hey") {
      this.$Notice.info({ title, desc });
    },
    s(desc, title = "Great!") {
      this.$Notice.success({ title, desc });
    },
    w(desc, title = "Oops!") {
      this.$Notice.warning({ title, desc });
    },
    e(desc, title = "Oops!") {
      this.$Notice.error({ title, desc });
    },
    swr(desc = "Someting went wrong! Please try again.", title = "Oops!") {
      this.$Notice.error({ title, desc });
    },
  }
}