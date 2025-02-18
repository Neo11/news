<?php
/**
 * Nextcloud - News
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later. See the COPYING file.
 *
 * @author Alessandro Cosentino <cosenal@gmail.com>
 * @author Bernhard Posselt <dev@bernhard-posselt.com>
 * @author Paul Tirk <paultirk@paultirk.com>
 * @copyright 2012 Alessandro Cosentino
 * @copyright 2012-2014 Bernhard Posselt
 * @copyright 2020 Paul Tirk
 */

return ['routes' => [
// page
['name' => 'page#index', 'url' => '/', 'verb' => 'GET'],
['name' => 'page#settings', 'url' => '/settings', 'verb' => 'GET'],
['name' => 'page#update_settings', 'url' => '/settings', 'verb' => 'PUT'],
['name' => 'page#manifest', 'url' => '/manifest.webapp', 'verb' => 'GET'],
['name' => 'page#explore', 'url' => '/explore/feeds.{lang}.json', 'verb' => 'GET'],

// admin
['name' => 'admin#update', 'url' => '/admin', 'verb' => 'PUT'],
['name' => 'admin#migrate', 'url' => '/admin/migrate', 'verb' => 'POST'],

// folders
['name' => 'folder#index', 'url' => '/folders', 'verb' => 'GET'],
['name' => 'folder#create', 'url' => '/folders', 'verb' => 'POST'],
['name' => 'folder#delete', 'url' => '/folders/{folderId}', 'verb' => 'DELETE'],
['name' => 'folder#restore', 'url' => '/folders/{folderId}/restore', 'verb' => 'POST'],
['name' => 'folder#rename', 'url' => '/folders/{folderId}/rename', 'verb' => 'POST'],
['name' => 'folder#read', 'url' => '/folders/{folderId}/read', 'verb' => 'POST'],
['name' => 'folder#open', 'url' => '/folders/{folderId}/open', 'verb' => 'POST'],

// feeds
['name' => 'feed#index', 'url' => '/feeds', 'verb' => 'GET'],
['name' => 'feed#create', 'url' => '/feeds', 'verb' => 'POST'],
['name' => 'feed#delete', 'url' => '/feeds/{feedId}', 'verb' => 'DELETE'],
['name' => 'feed#restore', 'url' => '/feeds/{feedId}/restore', 'verb' => 'POST'],
['name' => 'feed#read', 'url' => '/feeds/{feedId}/read', 'verb' => 'POST'],
['name' => 'feed#update', 'url' => '/feeds/{feedId}/update', 'verb' => 'POST'],
['name' => 'feed#active', 'url' => '/feeds/active', 'verb' => 'GET'],
['name' => 'feed#import', 'url' => '/feeds/import/articles', 'verb' => 'POST'],
['name' => 'feed#patch', 'url' => '/feeds/{feedId}', 'verb' => 'PATCH'],

// items
['name' => 'item#index', 'url' => '/items', 'verb' => 'GET'],
['name' => 'item#new_items', 'url' => '/items/new', 'verb' => 'GET'],
['name' => 'item#readAll', 'url' => '/items/read', 'verb' => 'POST'],
['name' => 'item#read', 'url' => '/items/{itemId}/read', 'verb' => 'POST'],
['name' => 'item#read_multiple', 'url' => '/items/read/multiple', 'verb' => 'POST'],
['name' => 'item#star', 'url' => '/items/{feedId}/{guidHash}/star', 'verb' => 'POST'],
['name' => 'item#share', 'url' => '/items/{itemId}/share/{shareRecipientId}', 'verb' => 'POST'],

// export
['name' => 'export#opml', 'url' => '/export/opml', 'verb' => 'GET'],
['name' => 'export#articles', 'url' => '/export/articles', 'verb' => 'GET'],

// general API
['name' => 'api#index', 'url' => '/api', 'verb' => 'GET'],

// API 2
['name' => 'folder_api_v2#create', 'url' => '/api/v2/folders', 'verb' => 'POST'],
['name' => 'folder_api_v2#update', 'url' => '/api/v2/folders/{folderId}', 'verb' => 'PATCH'],
['name' => 'folder_api_v2#delete', 'url' => '/api/v2/folders/{folderId}', 'verb' => 'DELETE'],

// API 1.3
['name' => 'utility_api#version', 'url' => '/api/v1-3/version', 'verb' => 'GET'],
['name' => 'utility_api#status', 'url' => '/api/v1-3/status', 'verb' => 'GET'],
['name' => 'utility_api#before_update', 'url' => '/api/v1-3/cleanup/before-update', 'verb' => 'GET'],
['name' => 'utility_api#after_update', 'url' => '/api/v1-3/cleanup/after-update', 'verb' => 'GET'],
['name' => 'utility_api#preflighted_cors', 'url' => '/api/v1-3/{path}', 'verb' => 'OPTIONS', 'requirements' => ['path' => '.+']],

// folders
['name' => 'folder_api#index', 'url' => '/api/v1-3/folders', 'verb' => 'GET'],
['name' => 'folder_api#create', 'url' => '/api/v1-3/folders', 'verb' => 'POST'],
['name' => 'folder_api#update', 'url' => '/api/v1-3/folders/{folderId}', 'verb' => 'PUT'],
['name' => 'folder_api#delete', 'url' => '/api/v1-3/folders/{folderId}', 'verb' => 'DELETE'],
['name' => 'folder_api#read', 'url' => '/api/v1-3/folders/{folderId}/read', 'verb' => 'POST'],

// feeds
['name' => 'feed_api#index', 'url' => '/api/v1-3/feeds', 'verb' => 'GET'],
['name' => 'feed_api#create', 'url' => '/api/v1-3/feeds', 'verb' => 'POST'],
['name' => 'feed_api#update', 'url' => '/api/v1-3/feeds/{feedId}', 'verb' => 'PUT'],
['name' => 'feed_api#delete', 'url' => '/api/v1-3/feeds/{feedId}', 'verb' => 'DELETE'],
['name' => 'feed_api#from_all_users', 'url' => '/api/v1-3/feeds/all', 'verb' => 'GET'],
['name' => 'feed_api#move', 'url' => '/api/v1-3/feeds/{feedId}/move', 'verb' => 'POST'],
['name' => 'feed_api#rename', 'url' => '/api/v1-3/feeds/{feedId}/rename', 'verb' => 'POST'],
['name' => 'feed_api#read', 'url' => '/api/v1-3/feeds/{feedId}/read', 'verb' => 'POST'],
['name' => 'feed_api#update', 'url' => '/api/v1-3/feeds/update', 'verb' => 'GET'],

// items
['name' => 'item_api#index', 'url' => '/api/v1-3/items', 'verb' => 'GET'],
['name' => 'item_api#updated', 'url' => '/api/v1-3/items/updated', 'verb' => 'GET'],
['name' => 'item_api#read', 'url' => '/api/v1-3/items/{itemId}/read', 'verb' => 'POST'],
['name' => 'item_api#unread', 'url' => '/api/v1-3/items/{itemId}/unread', 'verb' => 'POST'],
['name' => 'item_api#read_all', 'url' => '/api/v1-3/items/read', 'verb' => 'POST'],
['name' => 'item_api#read_multiple_by_ids', 'url' => '/api/v1-3/items/read/multiple', 'verb' => 'POST'],
['name' => 'item_api#unread_multiple_by_ids', 'url' => '/api/v1-3/items/unread/multiple', 'verb' => 'POST'],
['name' => 'item_api#star_by_item_id', 'url' => '/api/v1-3/items/{itemId}/star', 'verb' => 'POST'],
['name' => 'item_api#unstar_by_item_id', 'url' => '/api/v1-3/items/{itemId}/unstar', 'verb' => 'POST'],
['name' => 'item_api#star_multiple_by_item_ids', 'url' => '/api/v1-3/items/star/multiple', 'verb' => 'POST'],
['name' => 'item_api#unstar_multiple_by_item_ids', 'url' => '/api/v1-3/items/unstar/multiple', 'verb' => 'POST'],

// API 1.2
['name' => 'utility_api#version', 'url' => '/api/v1-2/version', 'verb' => 'GET'],
['name' => 'utility_api#status', 'url' => '/api/v1-2/status', 'verb' => 'GET'],
['name' => 'utility_api#before_update', 'url' => '/api/v1-2/cleanup/before-update', 'verb' => 'GET'],
['name' => 'utility_api#after_update', 'url' => '/api/v1-2/cleanup/after-update', 'verb' => 'GET'],
['name' => 'utility_api#preflighted_cors', 'url' => '/api/v1-2/{path}', 'verb' => 'OPTIONS', 'requirements' => ['path' => '.+']],

// folders
['name' => 'folder_api#index', 'url' => '/api/v1-2/folders', 'verb' => 'GET'],
['name' => 'folder_api#create', 'url' => '/api/v1-2/folders', 'verb' => 'POST'],
['name' => 'folder_api#update', 'url' => '/api/v1-2/folders/{folderId}', 'verb' => 'PUT'],
['name' => 'folder_api#delete', 'url' => '/api/v1-2/folders/{folderId}', 'verb' => 'DELETE'],
['name' => 'folder_api#read', 'url' => '/api/v1-2/folders/{folderId}/read', 'verb' => 'PUT'], // FIXME: POST would be more correct

// feeds
['name' => 'feed_api#index', 'url' => '/api/v1-2/feeds', 'verb' => 'GET'],
['name' => 'feed_api#create', 'url' => '/api/v1-2/feeds', 'verb' => 'POST'],
['name' => 'feed_api#update', 'url' => '/api/v1-2/feeds/{feedId}', 'verb' => 'PUT'],
['name' => 'feed_api#delete', 'url' => '/api/v1-2/feeds/{feedId}', 'verb' => 'DELETE'],
['name' => 'feed_api#from_all_users', 'url' => '/api/v1-2/feeds/all', 'verb' => 'GET'],
['name' => 'feed_api#move', 'url' => '/api/v1-2/feeds/{feedId}/move', 'verb' => 'PUT'], // FIXME: POST would be more correct
['name' => 'feed_api#rename', 'url' => '/api/v1-2/feeds/{feedId}/rename', 'verb' => 'PUT'], // FIXME: POST would be more correct
['name' => 'feed_api#read', 'url' => '/api/v1-2/feeds/{feedId}/read', 'verb' => 'PUT'], // FIXME: POST would be more correct
['name' => 'feed_api#update', 'url' => '/api/v1-2/feeds/update', 'verb' => 'GET'],

// items
['name' => 'item_api#index', 'url' => '/api/v1-2/items', 'verb' => 'GET'],
['name' => 'item_api#updated', 'url' => '/api/v1-2/items/updated', 'verb' => 'GET'],
['name' => 'item_api#read', 'url' => '/api/v1-2/items/{itemId}/read', 'verb' => 'PUT'], // FIXME: POST would be more correct
['name' => 'item_api#unread', 'url' => '/api/v1-2/items/{itemId}/unread', 'verb' => 'PUT'], // FIXME: POST would be more correct
['name' => 'item_api#read_all', 'url' => '/api/v1-2/items/read', 'verb' => 'PUT'], // FIXME: POST would be more correct
['name' => 'item_api#read_multiple', 'url' => '/api/v1-2/items/read/multiple', 'verb' => 'PUT'], // FIXME: POST would be more correct
['name' => 'item_api#unread_multiple', 'url' => '/api/v1-2/items/unread/multiple', 'verb' => 'PUT'], // FIXME: POST would be more correct
['name' => 'item_api#star', 'url' => '/api/v1-2/items/{feedId}/{guidHash}/star', 'verb' => 'PUT'], // FIXME: POST would be more correct
['name' => 'item_api#unstar', 'url' => '/api/v1-2/items/{feedId}/{guidHash}/unstar', 'verb' => 'PUT'], // FIXME: POST would be more correct
['name' => 'item_api#star_multiple', 'url' => '/api/v1-2/items/star/multiple', 'verb' => 'PUT'], // FIXME: POST would be more correct
['name' => 'item_api#unstar_multiple', 'url' => '/api/v1-2/items/unstar/multiple', 'verb' => 'PUT'], // FIXME: POST would be more correct

]];
