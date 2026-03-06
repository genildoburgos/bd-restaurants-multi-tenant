-- ============================================================================
-- PROJETO: Sistema de Gestao de Restaurantes Multi-Tenant
-- SCRIPT: Triggers do banco de dados
-- DISCIPLINA: Banco de Dados
-- ALUNOS: Douglas Henrique, Joaci Laurindo, Genildo Burgos, Antonio Marcos
-- DATA: 05/03/2026
-- ============================================================================

USE `laravel_restaurants`;

-- ============================================================================
-- TRIGGER 1: trg_atualiza_estoque_venda
-- TABELA: order_items
-- MOMENTO: AFTER INSERT
-- DESCRICAO: Decrementa o estoque do produto quando um item e adicionado
--            a um pedido. Usa GREATEST(0, ...) para evitar estoque negativo.
-- ============================================================================

DROP TRIGGER IF EXISTS trg_atualiza_estoque_venda;

DELIMITER $$

CREATE TRIGGER trg_atualiza_estoque_venda
AFTER INSERT ON order_items
FOR EACH ROW
BEGIN
    IF NEW.product_id IS NOT NULL THEN
        UPDATE products
        SET
            stock_quantity = GREATEST(0, stock_quantity - NEW.quantity),
            updated_at     = NOW()
        WHERE id = NEW.product_id
          AND stock_control = 1;
    END IF;
END$$

DELIMITER ;

-- ============================================================================
-- TRIGGER 2: trg_bloqueia_exclusao_produto
-- TABELA: products
-- MOMENTO: BEFORE DELETE
-- DESCRICAO: Impede a exclusao de um produto que possui pedidos ativos
--            (status diferente de 'delivered' e 'cancelled').
-- ============================================================================

DROP TRIGGER IF EXISTS trg_bloqueia_exclusao_produto;

DELIMITER $$

CREATE TRIGGER trg_bloqueia_exclusao_produto
BEFORE DELETE ON products
FOR EACH ROW
BEGIN
    DECLARE pedidos_ativos INT DEFAULT 0;

    SELECT COUNT(*) INTO pedidos_ativos
    FROM order_items oi
    INNER JOIN orders o ON oi.order_id = o.id
    WHERE oi.product_id = OLD.id
      AND o.status NOT IN ('delivered', 'cancelled');

    IF pedidos_ativos > 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Nao e possivel excluir: produto possui pedidos ativos em andamento.';
    END IF;
END$$

DELIMITER ;

-- ============================================================================
-- TRIGGER 3: trg_pontua_cliente_entrega
-- TABELA: orders
-- MOMENTO: AFTER UPDATE
-- DESCRICAO: Quando um pedido muda para 'delivered', acumula pontos de
--            fidelidade ao cliente (1 ponto por real gasto), incrementa
--            visitas e promove o nivel automaticamente:
--            bronze -> silver a partir de 200 pts
--            silver -> gold   a partir de 500 pts
-- ============================================================================

DROP TRIGGER IF EXISTS trg_pontua_cliente_entrega;

DELIMITER $$

CREATE TRIGGER trg_pontua_cliente_entrega
AFTER UPDATE ON orders
FOR EACH ROW
BEGIN
    DECLARE pts_ganhos INT;
    DECLARE total_pts  INT;

    IF NEW.status = 'delivered'
       AND OLD.status <> 'delivered'
       AND NEW.customer_id IS NOT NULL
    THEN
        SET pts_ganhos = FLOOR(NEW.total);

        UPDATE customers
        SET
            points      = points + pts_ganhos,
            updated_at  = NOW()
        WHERE id = NEW.customer_id;

        SELECT points INTO total_pts
        FROM customers
        WHERE id = NEW.customer_id;

        IF total_pts >= 500 THEN
            UPDATE customers
            SET level = 'gold', updated_at = NOW()
            WHERE id = NEW.customer_id AND level <> 'gold' AND level <> 'platinum';
        ELSEIF total_pts >= 200 THEN
            UPDATE customers
            SET level = 'silver', updated_at = NOW()
            WHERE id = NEW.customer_id AND level = 'bronze';
        END IF;
    END IF;
END$$

DELIMITER ;

-- ============================================================================
-- VERIFICACAO: Confirma que os 3 triggers foram criados
-- ============================================================================
SELECT
    TRIGGER_NAME     AS trigger_name,
    EVENT_MANIPULATION AS evento,
    EVENT_OBJECT_TABLE AS tabela,
    ACTION_TIMING    AS momento
FROM information_schema.TRIGGERS
WHERE TRIGGER_SCHEMA = 'laravel_restaurants'
ORDER BY TRIGGER_NAME;