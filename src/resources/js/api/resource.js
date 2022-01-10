import request from '@/utils/request';

/**
 * Simple RESTful resource class
 */
class Resource {
  constructor(uri) {
    this.uri = uri;
  }
  list(query) {
    return request({
      url: '/' + this.uri,
      method: 'get',
      params: query,
    });
  }
  get(id) {
    return request({
      url: '/' + this.uri + '/' + id,
      method: 'get',
    });
  }
  store(resource) {
    return request({
      url: '/' + this.uri,
      method: 'post',
      data: resource,
    });
  }
  update(id, resource) {
    return request({
      url: '/' + this.uri + '/' + id,
      method: 'put',
      data: resource,
    });
  }
  updateAll(resource) {
    return request({
      url: '/' + this.uri + '/all',
      method: 'post',
      data: resource,
    });
  }
  destroy(id, resource) {
    return request({
      url: '/' + this.uri + '/' + id,
      method: 'delete',
      data: resource,
    });
  }
}

export { Resource as default };
