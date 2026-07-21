import { BeeEntity } from './BeeEntity.js';
export class BeeTilemap extends BeeEntity {
    constructor({
        x = 0,
        y = 0,
        tiles = [],
        tileSize = 32,
        solidTiles = [],
        tileset = null,
        tilesetColumns = 1
    } = {}) {
        super({ x, y });

        this.tiles = tiles;
        this.tileSize = tileSize;
        this.solidTiles = solidTiles;

        this.tileset = tileset;
        this.tilesetColumns = tilesetColumns;

        this.rows = tiles.length;
        this.cols = tiles[0]?.length ?? 0;
    }

    getTile(col, row) {
        if (row < 0 || row >= this.rows) return null;
        if (col < 0 || col >= this.cols) return null;

        return this.tiles[row][col];
    }

    worldToTile(px, py) {
        const col = Math.floor((px - this.x) / this.tileSize);
        const row = Math.floor((py - this.y) / this.tileSize);

        return { col, row };
    }

    isSolidTile(col, row) {
        const tile = this.getTile(col, row);

        if (tile === null) return false;

        return this.solidTiles.includes(tile);
    }

    isSolidAtPixel(px, py) {
        const { col, row } = this.worldToTile(px, py);
        return this.isSolidTile(col, row);
    }

    entityCollides(entity) {
        if (!entity.collider) return false;

        const c = entity.collider;

        const left = c.x;
        const right = c.x + c.width;
        const top = c.y;
        const bottom = c.y + c.height;

        return (
            this.isSolidAtPixel(left, top) ||
            this.isSolidAtPixel(right, top) ||
            this.isSolidAtPixel(left, bottom) ||
            this.isSolidAtPixel(right, bottom)
        );
    }

    draw(ctx) {
        if (!this.visible) return;

        for (let row = 0; row < this.rows; row++) {
            for (let col = 0; col < this.cols; col++) {
                const tile = this.tiles[row][col];

                if (tile === 0) continue;

                const drawX = this.x + col * this.tileSize;
                const drawY = this.y + row * this.tileSize;

                if (this.tileset) {
                    const index = tile - 1;

                    const sx = (index % this.tilesetColumns) * this.tileSize;
                    const sy = Math.floor(index / this.tilesetColumns) * this.tileSize;

                    ctx.drawImage(
                        this.tileset,
                        sx,
                        sy,
                        this.tileSize,
                        this.tileSize,
                        drawX,
                        drawY,
                        this.tileSize,
                        this.tileSize
                    );
                } else {
                    ctx.fillStyle = this.solidTiles.includes(tile)
                        ? "#666"
                        : "#999";

                    ctx.fillRect(drawX, drawY, this.tileSize, this.tileSize);
                }
            }
        }

        super.draw(ctx);
    }
}
/** 🌟  * Classe BeeTilemap: Gestisce la mappa di gioco strutturata a griglia (tile).
 * Si occupa di renderizzare lo sfondo e i livelli visivi, e controlla 
 * la solidità dei singoli blocchi per gestire i muri e le collisioni del Player.
 */
