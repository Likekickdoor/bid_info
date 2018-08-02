// pages/detail/detail.js
var WxParse = require('../../wxParse/wxParse.js');
var content;
Page({

  /**
   * 页面的初始数据
   */
  data: {
  
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
  var that=this;
  wx.request({
    url: 'https://m.ctrltab.xyz/bid_info/search_detail',
    method: "GET",
    header: {
      "content-type": "application/json"
    },
    data:{
      bid:options.id
    },
    success:function(obj){
      content = obj.data.msg
      //收藏展示
      wx.request({
        url: 'https://m.ctrltab.xyz/bid_info/show',
        method: "GET",
        data: {
          classes: "collect",
          ye: 1
        },
        header: {
          "content-type": "application/json",
          "Cookie": "sessionId=" + wx.getStorageSync('sessionId')
        },
        success: function (obj) {
          for (var i in obj.data.ID) {
            if (content.bid == obj.data.ID[i]) {
              content.collect_sign = 1
            }
          }
          that.setData({
            content: content
          })
        }
      })
      var article=obj.data.msg.b_detail;
      WxParse.wxParse('article', 'html', article, that, 5);   
    }
  })
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
  collect: function (e) {
    var that = this;
    var jobid = e.currentTarget.dataset.jobid;
    wx.request({
      url: 'https://m.ctrltab.xyz/bid_info/collect',
      method: "GET",
      data: {
        id: jobid,
        status: 1
      },
      header: {
        "content-type": "application/x-www-form-urlencoded",
        "Cookie": "sessionId=" + wx.getStorageSync('sessionId')
      },
      success: function (obj) {

       content.collect_sign = 1
        that.setData({
          content: content
        })
      }
    })
  },
  nocollect: function (e) {
    var that = this;
    var jobid = e.currentTarget.dataset.jobid;
    wx.request({
      url: 'https://m.ctrltab.xyz/bid_info/collect',
      method: "GET",
      data: {
        id: jobid,
        status: 0
      },
      header: {
        "content-type": "application/x-www-form-urlencoded",
        "Cookie": "sessionId=" + wx.getStorageSync('sessionId')
      },
      success: function (obj) {
        content.collect_sign = 0
        that.setData({
          content: content
        })
      }
    })
  }
})