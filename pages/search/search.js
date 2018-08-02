// pages/search/search.js
var page=0;
var searchValue="";
var content;
var type= 4;
var page2=0;
var place = 'a';
var time = 30;

//关键搜索
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
      content = obj.data.msg;
      that.setData({
        content: obj.data.msg
      })
    }
  })
}

//筛选
function search2(that) {
  page2 = 1;
  wx.request({
    url: 'https://m.ctrltab.xyz/bid_info/fil',
    method: "GET",
    data: {
      type: type,
      place: place.substring(0, place.length - 1),
      time:time,
      ye:page2
    },
    header: {
      "content-type": "application/x-www-form-urlencoded" // 默认值
    },
    success: function (obj) {
      console.log(obj.data);
      content = obj.data;
      that.setData({
        content: obj.data
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
    console.log(options)
    searchValue = options.id;
    console.log(type)
    if (options.type){
      type = options.type
      search2(that);      
    }else{
      search(that);
    }
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
    console.log(this.data.index)
    if (this.data.index == 0) {
      type = 4;
      search2(this);
    } else if (this.data.index == 1) {
      type = 2
      search2(this);
    } else if (this.data.index == 2) {
      type = 1
      search2(this);
    } else if (this.data.index == 3) {
      type = 3
      search2(this);
    }
  },
  bindPickerChange1: function (e) {
    console.log('picker发送选择改变，携带值为', e.detail.value)
    this.setData({
      index: e.detail.value
    })
    if(this.data.index == 3){
      time = 30
      search2(this);      
    } else if (this.data.index == 2){
      time = 7
      search2(this);      
    } else if (this.data.index == 1){
      time = 3
      search2(this);      
    } else if (this.data.index == 0){
      time = 0
      search2(this);      
    }
  },
  bindRegionChange: function (e) {
    console.log('picker发送选择改变，携带值为', e.detail.value)
    this.setData({
      region: e.detail.value
    })
    console.log(this.data.region[0])
    if (this.data.region[0] == '全部'){
      place = 'a'
      search2(this);
    }else{
      place = this.data.region[0];
      search2(this);
    }
  },
  search: function (e) {
      searchValue= e.detail.value
  },
  searchSubmit: function () {
    var that=this;
    type=4;
    place='a';
    time=30;
    this.setData({
      value:'',
      array: ['所有', '货物类', '工程类', '服务类'],
      array1: ['今天', '近三天', '近一周', '近一个月'],
      index1: 0,
      index2: 3,
      region: ['全部', '全部', '全部'],
      customItem: '全部'
    })
    search(that);
  },
  onReachBottom: function () {
    var that = this;
    if(type!=4 || place!='a' || time!=30){
      page2 = page2 + 1;
      wx.request({
        url: 'https://m.ctrltab.xyz/bid_info/fil',
        method: "GET",
        data: {
          type: type,
          place: place.substring(0, place.length - 1),
          time: time,
          ye: page2
        },
        header: {
          "content-type": "application/x-www-form-urlencoded" // 默认值
        },
        success: function (obj) {
          var data1 = that.data.content;
          console.log(that.data)
          for (var i in obj.data) {
            data1.push(obj.data[i])
          }
          content = data1
          that.setData({
            content: data1
          })
        }
      })
    }else{
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
          content = data1
          that.setData({
            content: data1
          })
        }
      })
    }
  },
  skip: function (e) {
    console.log(e)
    var jobid = e.currentTarget.dataset.jobid;
    //历史记录存储
    wx.request({
      url: 'https://m.ctrltab.xyz/bid_info/history',
      method: "GET",
      data: {
        id: jobid,
        status: 1
      },
      header: {
        "content-type": "application/json",
        "Cookie": "sessionId=" + wx.getStorageSync('sessionId')
      },
      success: function (obj) {
        console.log(obj.data);
      }
    })
    wx.navigateTo({
      url: '../detail/detail?id=' + jobid,
    })
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
        console.log(obj)
        for (var i in content) {
          if (content[i].bid == jobid) {
            content[i].collect_sign = 1
          }
        }
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
        console.log(obj)
        for (var i in content) {
          if (content[i].bid == jobid) {
            content[i].collect_sign = 0
          }
        }
        that.setData({
          content: content
        })
      }
    })
  }
})