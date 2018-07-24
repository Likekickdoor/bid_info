// pages/search/search.js
var page=0;
var searchValue="";
function search(that) {
  page = 0;
  wx.request({
    url: 'https://m.ctrltab.xyz/bid_info/searchbid',
    method: "POST",
    data: {
      searhword: searchValue,
      startpage: page
    },
    header: {
      "content-type": "application/x-www-form-urlencoded" // 默认值
    },
    success: function (obj) {
      console.log(obj.data.msg);
      that.setData({
        content: obj.data.msg
      })
    }
  })
}
Page({

  /**
   * 页面的初始数据
   */
  data: {
    array: ['所有', '货物类', '工程类', '服务类'],
    array1:['今天','近三天','近一周','近一个月'],
    index1:0,
    index2:3,
    region: ['全部', '全部', '全部'],
    customItem: '全部'
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    var that=this;
    searchValue = options.id;
    search(that);
  },

  /**
   * 生命周期函数--监听页面初次渲染完成
   */
  onReady: function () {
  
  },

  /**
   * 生命周期函数--监听页面显示
   */
  onShow: function () {
  
  },

  /**
   * 生命周期函数--监听页面隐藏
   */
  onHide: function () {
  
  },

  /**
   * 生命周期函数--监听页面卸载
   */
  onUnload: function () {
  
  },

  /**
   * 页面相关事件处理函数--监听用户下拉动作
   */
  onPullDownRefresh: function () {
  
  },

  /**
   * 页面上拉触底事件的处理函数
   */
  onReachBottom: function () {
  
  },

  /**
   * 用户点击右上角分享
   */
  onShareAppMessage: function () {
  
  },
  bindPickerChange: function (e) {
    console.log('picker发送选择改变，携带值为', e)
    this.setData({
      index: e.detail.value
    })
  },
  bindPickerChange1: function (e) {
    console.log('picker发送选择改变，携带值为', e.detail.value)
    this.setData({
      index: e.detail.value
    })
  },
  bindRegionChange: function (e) {
    console.log('picker发送选择改变，携带值为', e.detail.value)
    this.setData({
      region: e.detail.value
    })
  },
  search: function (e) {
      searchValue= e.detail.value
  },
  searchSubmit: function () {
    var that=this;
    this.setData({
      value:''
    })
    search(that);
  },
  onReachBottom: function () {
    var that = this;
    page = page + 1;
    console.log(page)
    wx.request({
      url: 'https://m.ctrltab.xyz/bid_info/searchbid',
      method: "POST",
      data: {
        searhword: searchValue,
        startpage: page
      },
      header: {
        "content-type": "application/x-www-form-urlencoded" // 默认值
      },
      success: function (obj) {
        var data1 = that.data.content;
        console.log(that.data)
        for (var i in obj.data.msg) {
          data1.push(obj.data.msg[i])
        }
        that.setData({
          content: data1
        })
      }
    })
  },
  skip: function (e) {
    console.log(e)
    var jobid = e.currentTarget.dataset.jobid;
    wx.navigateTo({
      url: '../detail/detail?id=' + jobid,
    })
  }
})