// pages/index/index.js
var bmap = require('../../libs/bmap-wx.js');
var page=0;
Page({

  /**
   * 页面的初始数据
   */
  data: {
    userName:wx.getStorageSync('userName'),
    userPicture: wx.getStorageSync('userpicture'),
    searchValue:"",
    value:''
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    var that=this;
    page = 0;
    var place = wx.getStorageSync('location_place');
    console.log(wx.getStorageSync('sessionId'))
      wx.request({
        url: 'https://m.ctrltab.xyz/bid_info/recommend',
        method: "POST",
        data: {
          place: place.substring(0, place.length - 1),
          startpage: page
        },
        header: {
          "content-type": "application/json" ,
          "Cookie": "sessionId=" +  wx.getStorageSync('sessionId')
        },
        success: function (obj) {
          console.log(obj.data.msg);
          that.setData({
            content: obj.data.msg
          })
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
    var that = this;
    // 新建百度地图对象 
    wx.setStorageSync('tuijian', 0);
    var BMap = new bmap.BMapWX({
      ak: 'XIAIar2Lv2tbX1fPYul0BhGrrVGnDHmf'
    });
    var fail = function (data) {
      console.log(data)
    };
    var success = function (data) {
      console.log(data);
      wx.setStorageSync('location_place', data.originalData.result.addressComponent.city);
      that.setData({
        city: wx.getStorageSync('location_place')
      });
      console.log(wx.getStorageSync('location_place'))
    }
    that.setData({
      city: wx.getStorageSync('location_place')
    });
    // 发起regeocoding检索请求 
    BMap.regeocoding({
      fail: fail,
      success: success
    });
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
  place:function(){
    wx.redirectTo({
      url: '../place/place',
    })
  },
  searchSubmit:function(){
    var keyid = this.data.searchValue
    this.setData({
      value:''
    })
    wx.navigateTo({
      url: '../search/search?id=' + keyid,
    })
  },
  search:function(e){
    this.setData({
      searchValue: e.detail.value
    })
  },
  onReachBottom: function () {
    var that = this;
    page = page + 1;
    var place = wx.getStorageSync('location_place');
    console.log(wx.getStorageSync('id'))
    wx.request({
      url: 'https://m.ctrltab.xyz/bid_info/recommend',
      method: "POST",
      data: {
        place: place.substring(0, place.length - 1),
        startpage: page,

      },
      header: {
        "content-type": "application/x-www-form-urlencoded",
        "Cookie": "sessionId=" + wx.getStorageSync('sessionId')
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