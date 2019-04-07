// pages/welcome/welcome.js
var a=''
var b=''
var c=''
Page({

  /**
   * 页面的初始数据
   */
  data: {
    key1: "",
    key2: "",
    key3: "",
    save:false ,
    fail:false
  },
  
  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function(options) {
    var that=this
    console.log(wx.getStorageSync('sessionId'))
    
    //发起网络请求
    wx.request({
      url: 'https://m.ctrltab.xyz/bid_info/userkeyword',
      header: {
        "content-type": "application/json",
        "Cookie": "sessionId=" + wx.getStorageSync('sessionId')
      },
      method: "GET",
      success: function(obj) {
        console.log(obj.data);
        a = obj.data.msg.u_place
        b = obj.data.msg.u_ind_type,
        c = obj.data.msg.u_agent
        that.setData({
          value1: obj.data.msg.u_place,
          value2: obj.data.msg.u_ind_type,
          value3: obj.data.msg.u_agent

        })
      }
    })
  },

  /**
   * 生命周期函数--监听页面初次渲染完成
   */
  onReady: function() {

  },

  /**
   * 生命周期函数--监听页面显示
   */
  onShow: function() {

  },

  /**
   * 生命周期函数--监听页面隐藏
   */
  onHide: function() {

  },

  /**
   * 生命周期函数--监听页面卸载
   */
  onUnload: function() {

  },

  /**
   * 页面相关事件处理函数--监听用户下拉动作
   */
  onPullDownRefresh: function() {

  },

  /**
   * 页面上拉触底事件的处理函数
   */
  onReachBottom: function() {

  },

  /**
   * 用户点击右上角分享
   */
  onShareAppMessage: function() {

  },
  key1: function(e) {
    this.setData({
      key1: e.detail.value
    })
  },
  key2: function(e) {
    this.setData({
      key2: e.detail.value
    })
  },
  key3: function(e) {
    this.setData({
      key3: e.detail.value
    })
  },
  finish: function() {
    var that = this;
    wx.request({
      url: 'https://m.ctrltab.xyz/bid_info/updatekeyword',
      data: {
        u_place: that.data.key1,
        u_ind_type: that.data.key2,
        u_agent: that.data.key3
      },
      header: {
        "content-type": "application/json" ,
        "Cookie": "sessionId=" + wx.getStorageSync('sessionId')
      },
      method: "POST",
      success:function(obj){
        console.log(obj.data)
        if(obj.data.msg==true){
          that.setData({
            save:true,
            fail:false
          })
        }else{
          that.setData({
            save: false,
            fail: true
          })
        }
      }
    })
  },
  reset:function(){
    this.setData({
      value1:a,
      value2:b,
      value3:c
    })
  }
})