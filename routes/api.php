<?php

use Illuminate\Foundation\Http\Middleware\ValidatePostSize;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Jegex\Koboi\Http\Controllers\ActionController;
use Jegex\Koboi\Http\Controllers\AssociatableController;
use Jegex\Koboi\Http\Controllers\AttachableController;
use Jegex\Koboi\Http\Controllers\AttachedResourceUpdateController;
use Jegex\Koboi\Http\Controllers\CardController;
use Jegex\Koboi\Http\Controllers\CreationFieldController;
use Jegex\Koboi\Http\Controllers\CreationFieldSyncController;
use Jegex\Koboi\Http\Controllers\CreationPivotFieldController;
use Jegex\Koboi\Http\Controllers\DashboardCardController;
use Jegex\Koboi\Http\Controllers\DashboardController;
use Jegex\Koboi\Http\Controllers\DashboardMetricController;
use Jegex\Koboi\Http\Controllers\DetailMetricController;
use Jegex\Koboi\Http\Controllers\FieldAttachmentController;
use Jegex\Koboi\Http\Controllers\FieldController;
use Jegex\Koboi\Http\Controllers\FieldDestroyController;
use Jegex\Koboi\Http\Controllers\FieldDownloadController;
use Jegex\Koboi\Http\Controllers\FieldPreviewController;
use Jegex\Koboi\Http\Controllers\FilterController;
use Jegex\Koboi\Http\Controllers\ImpersonateController;
use Jegex\Koboi\Http\Controllers\LensActionController;
use Jegex\Koboi\Http\Controllers\LensCardController;
use Jegex\Koboi\Http\Controllers\LensController;
use Jegex\Koboi\Http\Controllers\LensFilterController;
use Jegex\Koboi\Http\Controllers\LensMetricController;
use Jegex\Koboi\Http\Controllers\LensResourceCountController;
use Jegex\Koboi\Http\Controllers\LensResourceDestroyController;
use Jegex\Koboi\Http\Controllers\LensResourceForceDeleteController;
use Jegex\Koboi\Http\Controllers\LensResourceRestoreController;
use Jegex\Koboi\Http\Controllers\MetricController;
use Jegex\Koboi\Http\Controllers\MorphableController;
use Jegex\Koboi\Http\Controllers\MorphedResourceAttachController;
use Jegex\Koboi\Http\Controllers\NotificationDeleteAllController;
use Jegex\Koboi\Http\Controllers\NotificationDeleteController;
use Jegex\Koboi\Http\Controllers\NotificationIndexController;
use Jegex\Koboi\Http\Controllers\NotificationReadAllController;
use Jegex\Koboi\Http\Controllers\NotificationReadController;
use Jegex\Koboi\Http\Controllers\NotificationUnreadController;
use Jegex\Koboi\Http\Controllers\PivotFieldDestroyController;
use Jegex\Koboi\Http\Controllers\RelatableAuthorizationController;
use Jegex\Koboi\Http\Controllers\ResourceAttachController;
use Jegex\Koboi\Http\Controllers\ResourceCountController;
use Jegex\Koboi\Http\Controllers\ResourceDestroyController;
use Jegex\Koboi\Http\Controllers\ResourceDetachController;
use Jegex\Koboi\Http\Controllers\ResourceForceDeleteController;
use Jegex\Koboi\Http\Controllers\ResourceIndexController;
use Jegex\Koboi\Http\Controllers\ResourcePeekController;
use Jegex\Koboi\Http\Controllers\ResourcePreviewController;
use Jegex\Koboi\Http\Controllers\ResourceRestoreController;
use Jegex\Koboi\Http\Controllers\ResourceSearchController;
use Jegex\Koboi\Http\Controllers\ResourceShowController;
use Jegex\Koboi\Http\Controllers\ResourceStoreController;
use Jegex\Koboi\Http\Controllers\ResourceUpdateController;
use Jegex\Koboi\Http\Controllers\SearchController;
use Jegex\Koboi\Http\Controllers\SoftDeleteStatusController;
use Jegex\Koboi\Http\Controllers\UpdateFieldController;
use Jegex\Koboi\Http\Controllers\UpdatePivotFieldController;

// Global Search...
Route::get('/search', SearchController::class);

// Impersonation...
Route::post('impersonate', [ImpersonateController::class, 'startImpersonating'])->name('start-nova-impersonation');
Route::delete('impersonate', [ImpersonateController::class, 'stopImpersonating'])->name('stop-nova-impersonation');

// Fields...
Route::get('/{resource}/field/{field}', FieldController::class);
Route::middleware(ValidatePostSize::class)
    ->group(static function (Router $router) {
        $router->post('/{resource}/field/{field}/preview', [FieldPreviewController::class, 'create']);
        $router->post('/{resource}/{resourceId}/field/{field}/preview', [FieldPreviewController::class, 'update']);
        $router->post('/{resource}/field-attachment/{field}', [FieldAttachmentController::class, 'store']);
    });
Route::delete('/{resource}/field-attachment/{field}', [FieldAttachmentController::class, 'destroyAttachment']);
Route::get('/{resource}/field-attachment/{field}/draftId', [FieldAttachmentController::class, 'draftId']);
Route::delete('/{resource}/field-attachment/{field}/{draftId}', [FieldAttachmentController::class, 'destroyPending']);
Route::get('/{resource}/creation-fields', CreationFieldController::class);
Route::get('/{resource}/{resourceId}/update-fields', UpdateFieldController::class);
Route::get('/{resource}/{resourceId}/creation-pivot-fields/{relatedResource}', CreationPivotFieldController::class);
Route::get('/{resource}/{resourceId}/update-pivot-fields/{relatedResource}/{relatedResourceId}', UpdatePivotFieldController::class);
Route::middleware(ValidatePostSize::class)
    ->group(static function (Router $router) {
        $router->patch('/{resource}/creation-fields', CreationFieldSyncController::class);
        $router->patch('/{resource}/{resourceId}/update-fields', [UpdateFieldController::class, 'sync']);
        $router->patch('/{resource}/{resourceId}/creation-pivot-fields/{relatedResource}', [CreationPivotFieldController::class, 'sync']);
        $router->patch('/{resource}/{resourceId}/update-pivot-fields/{relatedResource}/{relatedResourceId}', [UpdatePivotFieldController::class, 'sync']);
        $router->post('/{resource}/{resourceId}/field/{field}/preview/{relatedResource}', [FieldPreviewController::class, 'createPivot']);
        $router->post('/{resource}/{resourceId}/field/{field}/preview/{relatedResource}/{relatedResourceId}', [FieldPreviewController::class, 'updatePivot']);
    });
Route::get('/{resource}/{resourceId}/download/{field}', FieldDownloadController::class);
Route::delete('/{resource}/{resourceId}/field/{field}', FieldDestroyController::class);
Route::delete('/{resource}/{resourceId}/{relatedResource}/{relatedResourceId}/field/{field}', PivotFieldDestroyController::class);

// Dashboards...
Route::get('/dashboards/{dashboard}', DashboardController::class);
Route::get('/dashboards/cards/{dashboard}', DashboardCardController::class);
Route::get('/dashboards/cards/{dashboard}/metrics/{metric}', DashboardMetricController::class);

// Notifications...
Route::get('/nova-notifications', NotificationIndexController::class);
Route::post('/nova-notifications/read-all', NotificationReadAllController::class);
Route::post('/nova-notifications/{notification}/read', NotificationReadController::class);
Route::post('/nova-notifications/{notification}/unread', NotificationUnreadController::class);
Route::delete('/nova-notifications/', NotificationDeleteAllController::class);
Route::delete('/nova-notifications/{notification}', NotificationDeleteController::class);

// Actions...
Route::get('/{resource}/actions', [ActionController::class, 'index']);
Route::post('/{resource}/action', [ActionController::class, 'store'])->middleware(ValidatePostSize::class);
Route::patch('/{resource}/action', [ActionController::class, 'sync']);

// Filters...
Route::get('/{resource}/filters', FilterController::class);

// Lenses...
Route::get('/{resource}/lenses', [LensController::class, 'index']);
Route::get('/{resource}/lens/{lens}', [LensController::class, 'show']);
Route::get('/{resource}/lens/{lens}/count', LensResourceCountController::class);
Route::delete('/{resource}/lens/{lens}', LensResourceDestroyController::class);
Route::delete('/{resource}/lens/{lens}/force', LensResourceForceDeleteController::class);
Route::put('/{resource}/lens/{lens}/restore', LensResourceRestoreController::class);
Route::get('/{resource}/lens/{lens}/actions', [LensActionController::class, 'index']);
Route::post('/{resource}/lens/{lens}/action', [LensActionController::class, 'store'])->middleware(ValidatePostSize::class);
Route::patch('/{resource}/lens/{lens}/action', [LensActionController::class, 'sync']);
Route::get('/{resource}/lens/{lens}/filters', [LensFilterController::class, 'index']);

// Cards / Metrics...
Route::get('/{resource}/metrics', [MetricController::class, 'index']);
Route::get('/{resource}/metrics/{metric}', [MetricController::class, 'show']);
Route::get('/{resource}/{resourceId}/metrics/{metric}', DetailMetricController::class);

Route::get('/{resource}/lens/{lens}/metrics', [LensMetricController::class, 'index']);
Route::get('/{resource}/lens/{lens}/metrics/{metric}', [LensMetricController::class, 'show']);

Route::get('/{resource}/cards', CardController::class);
Route::get('/{resource}/lens/{lens}/cards', LensCardController::class);

// Authorization Information...
Route::get('/{resource}/relate-authorization', RelatableAuthorizationController::class);

// Soft Delete Information...
Route::get('/{resource}/soft-deletes', SoftDeleteStatusController::class);

// Resource Management...
Route::get('/{resource}', ResourceIndexController::class);
Route::get('/{resource}/search', ResourceSearchController::class);
Route::get('/{resource}/count', ResourceCountController::class);
Route::delete('/{resource}/detach', ResourceDetachController::class);
Route::put('/{resource}/restore', ResourceRestoreController::class);
Route::delete('/{resource}/force', ResourceForceDeleteController::class);
Route::get('/{resource}/{resourceId}', ResourceShowController::class);
Route::get('/{resource}/{resourceId}/preview', ResourcePreviewController::class);
Route::get('/{resource}/{resourceId}/peek', ResourcePeekController::class);
Route::middleware(ValidatePostSize::class)
    ->group(static function (Router $router) {
        $router->post('/{resource}', ResourceStoreController::class);
        $router->put('/{resource}/{resourceId}', ResourceUpdateController::class);
    });
Route::delete('/{resource}', ResourceDestroyController::class);

// Associatable Resources...
Route::get('/{resource}/associatable/{field}', AssociatableController::class);
Route::get('/{resource}/attachable/{field}', AttachableController::class);
Route::get('/{resource}/{resourceId}/attachable/{field}', AttachableController::class);
Route::get('/{resource}/morphable/{field}', MorphableController::class);

// Resource Attachment...
Route::middleware(ValidatePostSize::class)
    ->group(static function (Router $router) {
        $router->post('/{resource}/{resourceId}/attach/{relatedResource}', ResourceAttachController::class);
        $router->post('/{resource}/{resourceId}/update-attached/{relatedResource}/{relatedResourceId}', AttachedResourceUpdateController::class);
        $router->post('/{resource}/{resourceId}/attach-morphed/{relatedResource}', MorphedResourceAttachController::class);
    });
