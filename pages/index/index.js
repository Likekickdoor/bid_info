// pages/index/index.js
var bmap = require('../../libs/bmap-wx.js');
Page({

  /**
   * 页面的初始数据
   */
  data: {
    userName:wx.getStorageSync('userName'),
    userPicture: wx.getStorageSync('userpicture')
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {

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
    wx.navigateTo({
      url: '../search/search',
    })
  }
})