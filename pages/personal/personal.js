

Page({
  navigateSkip: function () {
    wx.navigateTo({
      title: "goback",
      url: '../keyy/keyy'
    })
  },
  navigateBack: function () {
    wx.navigateTo({
      title: "goback",
      url: '../collection/collection'
    })
  },
  Back: function () {
    wx.navigateTo({
      title: "goback",
      url: '../history/history'
    })
  },
  
})

